<?php
/**
 * UPHSL Admin Create Post
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Administrative interface for creating new blog posts and news articles
 */

require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
// Session is automatically initialized by security.php

// Check if user is logged in and has appropriate role
if (!isLoggedIn() || (!isAuthor() && !isAdmin() && !isSuperAdmin())) {
    redirect('../auth/login.php');
}

$user = getUserById($_SESSION['user_id']);
$userRole = $_SESSION['user_role'];

// Set page title for header
$page_title = 'Create Post';

$error = '';
$success = '';
$isEdit = false;
$post = null;

// Get all categories from database
$pdo = getDBConnection();
$allCategories = getAllCategories();

// Define navbar items configuration (matching header.php)
$navbar_items_config = [
    'programs' => [
        'bs-nursing' => 'BS Nursing',
        'bs-physical-therapy' => 'BS Physical Therapy',
        'bs-information-technology' => 'BS Information Technology',
        'bs-computer-science' => 'BS Computer Science',
        'ba-communication-arts' => 'BA Communication Arts',
        'senior-high-school' => 'Senior High School Tracks/Strands',
        'bs-engineering' => 'BS Engineering',
        'bs-accountancy' => 'BS Accountancy',
        'bs-business-administration' => 'BS Business Administration',
        'bachelor-secondary-education' => 'Bachelor of Secondary Education',
        'call-center-training' => 'Call Center Training Program',
        'basic-education' => 'Basic Education',
        'bachelor-elementary-education' => 'Bachelor of Elementary Education',
        'bs-hospitality-management' => 'BS Hospitality Management',
        'bs-tourism-management' => 'BS Tourism Management',
        'ab-bs-psychology' => 'AB/BS Psychology',
        'english-proficiency-training' => 'English Proficiency Training'
    ],
    'academics' => [
        'admission-scholarship' => 'Admission & Scholarship',
        'registrar' => 'Registrar',
        'library' => 'Library',
        'alumni' => 'Alumni'
    ]
];

// Get program and academics category names from navbar config
$programNames = array_values($navbar_items_config['programs']);
$academicsNames = array_values($navbar_items_config['academics']);

// Create a map of existing categories by name
$existingCategoriesMap = [];
foreach ($allCategories as $category) {
    $existingCategoriesMap[$category['name']] = $category;
}

// Ensure all navbar categories exist in database, create if missing
$allNavbarCategoryNames = array_merge($programNames, $academicsNames);
$categoriesCreated = false;
foreach ($allNavbarCategoryNames as $categoryName) {
    if (!isset($existingCategoriesMap[$categoryName])) {
        // Category doesn't exist, try to get it first (might have been created by another process)
        $existingCategory = getCategoryByName($categoryName);
        if ($existingCategory) {
            $existingCategoriesMap[$categoryName] = $existingCategory;
        } else {
            // Category doesn't exist, create it
            try {
                // Generate slug from category name
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $categoryName)));
                $slug = preg_replace('/-+/', '-', $slug); // Replace multiple dashes with single dash
                $slug = trim($slug, '-'); // Remove leading/trailing dashes
                
                // Create new category with slug
                $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
                $stmt->execute([$categoryName, $slug]);
                $newCategoryId = $pdo->lastInsertId();
                // Fetch the newly created category to get all fields
                $newCategory = getCategoryById($newCategoryId);
                if ($newCategory) {
                    $existingCategoriesMap[$categoryName] = $newCategory;
                    $categoriesCreated = true;
                } else {
                    // Fallback: create array structure
                    $existingCategoriesMap[$categoryName] = [
                        'id' => $newCategoryId,
                        'name' => $categoryName,
                        'slug' => $slug
                    ];
                    $categoriesCreated = true;
                }
            } catch (PDOException $e) {
                // Category might have been created by another request, try to fetch it again
                $existingCategory = getCategoryByName($categoryName);
                if ($existingCategory) {
                    $existingCategoriesMap[$categoryName] = $existingCategory;
                } else {
                    error_log("Failed to create category: " . $categoryName . " - " . $e->getMessage());
                }
            }
        }
    }
}

// If categories were created, refresh the allCategories list
if ($categoriesCreated) {
    $allCategories = getAllCategories();
    // Rebuild the map with all categories including newly created ones
    $existingCategoriesMap = [];
    foreach ($allCategories as $category) {
        $existingCategoriesMap[$category['name']] = $category;
    }
}

// Organize categories by type for display
$programCategories = [];
$academicsCategories = [];

// Sort programs by navbar order
foreach ($programNames as $programName) {
    if (isset($existingCategoriesMap[$programName]) && is_array($existingCategoriesMap[$programName])) {
        $cat = $existingCategoriesMap[$programName];
        // Ensure the category has an id field
        if (isset($cat['id']) && isset($cat['name'])) {
            $programCategories[] = $cat;
        }
    }
}

// Sort academics by navbar order
foreach ($academicsNames as $academicsName) {
    if (isset($existingCategoriesMap[$academicsName]) && is_array($existingCategoriesMap[$academicsName])) {
        $cat = $existingCategoriesMap[$academicsName];
        // Ensure the category has an id field
        if (isset($cat['id']) && isset($cat['name'])) {
            $academicsCategories[] = $cat;
        }
    }
}

// Check if this is an edit request
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $isEdit = true;
    $postId = (int)$_GET['edit'];
    
    // Get the post data
    $pdo = getDBConnection();
    
    // Different query based on user role
    if (isAuthor()) {
        // Authors can only edit their own posts
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND author_id = ?");
        $stmt->execute([$postId, $_SESSION['user_id']]);
    } else {
        // Admins and Super Admins can edit any post
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$postId]);
    }
    
    $post = $stmt->fetch();
    
    if (!$post) {
        $error = 'Post not found or you do not have permission to edit it';
        $isEdit = false;
    } else {
        $page_title = 'Edit Post';
        // category_id is already in the post data from the SELECT query
        
        // Get existing SDG tags for this post
        $stmt = $pdo->prepare("SELECT sdg_number FROM post_sdg_tags WHERE post_id = ?");
        $stmt->execute([$postId]);
        $existingSdgTags = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}

// SDG Goals array for tag selection
$sdgGoals = [
    1 => 'No Poverty',
    2 => 'Zero Hunger',
    3 => 'Good Health and Well-being',
    4 => 'Quality Education',
    5 => 'Gender Equality',
    6 => 'Clean Water and Sanitation',
    7 => 'Affordable and Clean Energy',
    8 => 'Decent Work and Economic Growth',
    9 => 'Industry, Innovation and Infrastructure',
    10 => 'Reduced Inequalities',
    11 => 'Sustainable Cities and Communities',
    12 => 'Responsible Consumption and Production',
    13 => 'Climate Action',
    14 => 'Life Below Water',
    15 => 'Life on Land',
    16 => 'Peace, Justice and Strong Institutions',
    17 => 'Partnerships for the Goals'
];

// Initialize existingSdgTags if not set
if (!isset($existingSdgTags)) {
    $existingSdgTags = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!CSRF::verify()) {
        $error = 'Security token mismatch. Please refresh the page and try again.';
    } else {
    error_log("Form submitted - POST data: " . print_r($_POST, true));
    error_log("Form submitted - FILES data: " . print_r($_FILES, true));
    
        $title = Validator::sanitize($_POST['title'], 'string');
        $content = $_POST['content']; // Rich text content - sanitized on output
        $status = Validator::sanitize($_POST['status'] ?? 'draft', 'string');
        $excerpt = Validator::sanitize($_POST['excerpt'] ?? '', 'string');
        $publishedDate = Validator::sanitize($_POST['published_date'] ?? null, 'string');
    $categoryId = isset($_POST['category']) && is_numeric($_POST['category']) ? (int)$_POST['category'] : null;
    $isEdit = isset($_POST['is_edit']) && $_POST['is_edit'] === '1';
    $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    
    if (empty($title) || empty($content)) {
        $error = 'Please fill in all required fields';
    } else {
        $pdo = getDBConnection();
        
        // Check if images are provided (for new posts) or exist (for edited posts)
        $hasImages = false;
        if ($isEdit && $postId > 0) {
            // For edits, allow save if:
            // - the post already has at least one record in post_images, OR
            // - the post has a featured_image set, OR
            // - new images are being uploaded
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM post_images WHERE post_id = ?");
            $stmt->execute([$postId]);
            $result = $stmt->fetch();
            $existingImageCount = isset($result['count']) ? (int)$result['count'] : 0;
            $hasExistingImageRecords = $existingImageCount > 0;
            
            // Get featured_image from database if not already loaded
            $hasFeaturedImage = false;
            if (isset($post['featured_image']) && !empty($post['featured_image'])) {
                $hasFeaturedImage = true;
            } else {
                // Query database for featured_image
                $stmt = $pdo->prepare("SELECT featured_image FROM posts WHERE id = ?");
                $stmt->execute([$postId]);
                $postData = $stmt->fetch();
                $hasFeaturedImage = !empty($postData['featured_image'] ?? '');
            }
            
            $hasNewUploads = !empty($_FILES['images']['name'][0]);
            $hasImages = $hasExistingImageRecords || $hasFeaturedImage || $hasNewUploads;
        } else {
            // For new posts, at least one image upload is required
            $hasImages = !empty($_FILES['images']['name'][0]);
        }
        
        if (!$hasImages) {
            $error = 'Please attach at least one image. Images are required for posts.';
        } else {
        
        try {
            // Start transaction
            $pdo->beginTransaction();
            error_log("Transaction started");
            
            if ($isEdit && $postId > 0) {
                // Generate new slug from title when updating
                $slug = generateUniqueSlug($title, 'posts', $postId);
                
                // Update existing post - different query based on user role
                if (isAuthor()) {
                    // Authors can only update their own posts
                    $stmt = $pdo->prepare("
                        UPDATE posts 
                        SET title = ?, slug = ?, content = ?, excerpt = ?, status = ?, published_at = ?, category_id = ?, updated_at = CURRENT_TIMESTAMP 
                        WHERE id = ? AND author_id = ?
                    ");
                    $stmt->execute([$title, $slug, $content, $excerpt, $status, $publishedDate, $categoryId, $postId, $_SESSION['user_id']]);
                } else {
                    // Admins and Super Admins can update any post
                    $stmt = $pdo->prepare("
                        UPDATE posts 
                        SET title = ?, slug = ?, content = ?, excerpt = ?, status = ?, published_at = ?, category_id = ?, updated_at = CURRENT_TIMESTAMP 
                        WHERE id = ?
                    ");
                    $stmt->execute([$title, $slug, $content, $excerpt, $status, $publishedDate, $categoryId, $postId]);
                }
                
                if ($stmt->rowCount() === 0) {
                    throw new Exception('Post not found or you do not have permission to edit it');
                }
            } else {
                // Create new post
                $slug = generateUniqueSlug($title);
                $stmt = $pdo->prepare("
                    INSERT INTO posts (title, slug, content, excerpt, status, published_at, category_id, author_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$title, $slug, $content, $excerpt, $status, $publishedDate, $categoryId, $_SESSION['user_id']]);
                $postId = $pdo->lastInsertId();
            }
            
            // Get current featured image path before any operations
            $stmt = $pdo->prepare("SELECT featured_image FROM posts WHERE id = ?");
            $stmt->execute([$postId]);
            $currentPost = $stmt->fetch();
            $currentFeaturedImage = $currentPost['featured_image'] ?? '';
            
            // Check current image count before deletion
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM post_images WHERE post_id = ?");
            $stmt->execute([$postId]);
            $currentImageCount = $stmt->fetch()['count'];
            $deletingCount = isset($_POST['delete_images']) && is_array($_POST['delete_images']) && !empty($_POST['delete_images']) ? count(array_filter($_POST['delete_images'], 'is_numeric')) : 0;
            $uploadingCount = !empty($_FILES['images']['name'][0]) ? count(array_filter($_FILES['images']['name'])) : 0;
            
            // Validate that at least one image will remain after deletion
            // For edits, allow if: post_images will remain, OR featured_image exists, OR new images are being uploaded
            if ($isEdit) {
                $imagesAfterDeletion = $currentImageCount - $deletingCount + $uploadingCount;
                $hasFeaturedImageFallback = !empty($currentFeaturedImage);
                
                if ($imagesAfterDeletion < 1 && !$hasFeaturedImageFallback && $uploadingCount === 0) {
                    throw new Exception('At least one image is required. Please keep existing images or upload new ones.');
                }
            }
            
            // Handle deletion of existing images
            $deletedFeaturedImage = false;
            if (isset($_POST['delete_images']) && is_array($_POST['delete_images']) && !empty($_POST['delete_images'])) {
                $uploadDir = dirname(__DIR__) . '/uploads/';
                
                foreach ($_POST['delete_images'] as $imageId) {
                    // Validate image ID is numeric
                    $imageId = filter_var($imageId, FILTER_VALIDATE_INT);
                    if ($imageId === false || $imageId <= 0) {
                        continue; // Skip invalid image IDs
                    }
                    
                    // Get image path before deleting
                    $stmt = $pdo->prepare("SELECT image_path FROM post_images WHERE id = ? AND post_id = ?");
                    $stmt->execute([$imageId, $postId]);
                    $image = $stmt->fetch();
                    
                    if ($image) {
                        // Check if this is the featured image
                        if ($currentFeaturedImage && $image['image_path'] === $currentFeaturedImage) {
                            $deletedFeaturedImage = true;
                        }
                        
                        // Delete from database
                        $stmt = $pdo->prepare("DELETE FROM post_images WHERE id = ?");
                        $stmt->execute([$imageId]);
                        
                        // Delete file from server - convert relative path to absolute
                        $imagePath = $image['image_path'];
                        // If path is relative (starts with 'uploads/'), convert to absolute
                        if (strpos($imagePath, 'uploads/') === 0) {
                            $absolutePath = dirname(__DIR__) . '/' . $imagePath;
                        } else {
                            $absolutePath = $imagePath;
                        }
                        
                        if (file_exists($absolutePath)) {
                            @unlink($absolutePath); // Suppress errors if file doesn't exist
                        }
                    }
                }
            }
            
            // Handle image uploads
            error_log("Image upload debug - FILES array: " . print_r($_FILES, true));
            error_log("Image upload debug - Checking if images exist: " . (isset($_FILES['images']) ? 'yes' : 'no'));
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                error_log("Image upload: Processing " . count($_FILES['images']['name']) . " files");
                $uploadDir = dirname(__DIR__) . '/uploads/';
                error_log("Current working directory: " . getcwd());
                error_log("Upload directory path: " . $uploadDir);
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                    error_log("Created upload directory: $uploadDir");
                }
                
                $uploadedImages = [];
                // Filter out empty file names to get actual count
                $imageCount = count(array_filter($_FILES['images']['name']));
                
                if ($imageCount === 0) {
                    error_log("No valid images found in upload");
                    throw new Exception('No valid images were uploaded. Please select at least one image file.');
                }
                
                error_log("Processing $imageCount image(s)");
                $actualIndex = 0;
                for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                    // Skip empty file names
                    if (empty($_FILES['images']['name'][$i])) {
                        continue;
                    }
                    
                    error_log("Processing image $i: " . $_FILES['images']['name'][$i] . " (error: " . $_FILES['images']['error'][$i] . ")");
                    if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                        $fileName = $_FILES['images']['name'][$i];
                        $fileTmpName = $_FILES['images']['tmp_name'][$i];
                        $fileSize = $_FILES['images']['size'][$i];
                        $fileType = $_FILES['images']['type'][$i];
                        
                        // Validate file extension
                        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        if (!in_array($fileExtension, $allowedExtensions)) {
                            throw new Exception("Invalid file extension for $fileName. Only JPEG, PNG, GIF, and WebP are allowed.");
                        }
                        
                        // Validate file size (10MB max)
                        if ($fileSize > 10 * 1024 * 1024) {
                            throw new Exception("File $fileName is too large. Maximum size is 10MB.");
                        }
                        
                        // Verify actual file content using getimagesize (more secure than trusting MIME type)
                        $imageInfo = @getimagesize($fileTmpName);
                        if ($imageInfo === false) {
                            throw new Exception("File $fileName is not a valid image.");
                        }
                        
                        // Verify MIME type matches actual file content
                        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                        $detectedMime = $imageInfo['mime'];
                        if (!in_array($detectedMime, $allowedTypes)) {
                            throw new Exception("Invalid file type detected for $fileName. Only JPEG, PNG, GIF, and WebP are allowed.");
                        }
                        
                        // Generate unique filename
                        $uniqueFileName = uniqid() . '_' . time() . '.' . $fileExtension;
                        
                        // Ensure filename doesn't contain path traversal - use basename for safety
                        $safeFileName = basename($uniqueFileName);
                        
                        // Construct upload path - ensure directory exists and use absolute path
                        $realUploadDir = realpath($uploadDir);
                        if ($realUploadDir === false) {
                            // If realpath fails, use the original path (directory should exist from mkdir above)
                            $uploadPath = rtrim($uploadDir, '/\\') . DIRECTORY_SEPARATOR . $safeFileName;
                        } else {
                            $uploadPath = $realUploadDir . DIRECTORY_SEPARATOR . $safeFileName;
                        }
                        
                        if (move_uploaded_file($fileTmpName, $uploadPath)) {
                            error_log("Image uploaded successfully: $uploadPath");
                            
                            // Verify file was actually moved
                            if (!file_exists($uploadPath)) {
                                throw new Exception("Image file was not saved correctly: $uploadPath");
                            }
                            
                            // Optimize image for better performance
                            optimizeImage($uploadPath, $detectedMime);
                            
                            // Store relative path in database (from root directory)
                            $relativePath = 'uploads/' . $safeFileName;
                            
                            // Insert image record
                            $imageStmt = $pdo->prepare("
                                INSERT INTO post_images (post_id, image_path, sort_order) 
                                VALUES (?, ?, ?)
                            ");
                            if ($imageStmt->execute([$postId, $relativePath, $actualIndex])) {
                                $uploadedImages[] = $relativePath;
                                error_log("Image record inserted into database with path: $relativePath for post ID: $postId");
                                $actualIndex++;
                            } else {
                                $errorInfo = $imageStmt->errorInfo();
                                error_log("Failed to insert image record: " . print_r($errorInfo, true));
                                throw new Exception("Failed to save image record to database: " . ($errorInfo[2] ?? 'Unknown error'));
                            }
                        } else {
                            $errorMsg = "Failed to move uploaded file: $fileTmpName to $uploadPath";
                            error_log($errorMsg);
                            // Check if it's a permissions issue
                            if (!is_writable($uploadDir)) {
                                throw new Exception("Upload directory is not writable: $uploadDir");
                            }
                            throw new Exception($errorMsg);
                        }
                    } else {
                        error_log("Image upload error for file $i: " . $_FILES['images']['error'][$i]);
                        $errorMessages = [
                            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
                            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
                            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
                            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
                            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
                        ];
                        $errorMsg = $errorMessages[$_FILES['images']['error'][$i]] ?? 'Unknown upload error';
                        throw new Exception("Image upload failed: $errorMsg");
                    }
                }
                
                // Handle clearing featured_image if requested (when user removes featured_image and uploads new images)
                if (isset($_POST['clear_featured_image']) && $_POST['clear_featured_image'] === '1') {
                    $clearStmt = $pdo->prepare("UPDATE posts SET featured_image = NULL WHERE id = ?");
                    $clearStmt->execute([$postId]);
                    $currentFeaturedImage = ''; // Clear the variable
                }
                
                // Set featured image (first uploaded image if new images were uploaded)
                if (!empty($uploadedImages)) {
                    $featuredStmt = $pdo->prepare("
                        UPDATE posts SET featured_image = ? WHERE id = ?
                    ");
                    $featuredStmt->execute([$uploadedImages[0], $postId]);
                } elseif ($deletedFeaturedImage || empty($currentFeaturedImage)) {
                    // If featured image was deleted or doesn't exist, set it to the first remaining image
                    $stmt = $pdo->prepare("SELECT image_path FROM post_images WHERE post_id = ? ORDER BY sort_order ASC, created_at ASC LIMIT 1");
                    $stmt->execute([$postId]);
                    $firstImage = $stmt->fetch();
                    if ($firstImage) {
                        $featuredStmt = $pdo->prepare("
                            UPDATE posts SET featured_image = ? WHERE id = ?
                        ");
                        $featuredStmt->execute([$firstImage['image_path'], $postId]);
                    }
                }
            }
            
            // Handle SDG tags (optional)
            // Delete existing SDG tags for this post
            $stmt = $pdo->prepare("DELETE FROM post_sdg_tags WHERE post_id = ?");
            $stmt->execute([$postId]);
            
            // Insert new SDG tags if provided
            if (isset($_POST['sdg_tags']) && is_array($_POST['sdg_tags'])) {
                $sdgTags = array_filter(array_map('intval', $_POST['sdg_tags']), function($num) {
                    return $num >= 1 && $num <= 17;
                });
                
                if (!empty($sdgTags)) {
                    $stmt = $pdo->prepare("INSERT INTO post_sdg_tags (post_id, sdg_number) VALUES (?, ?)");
                    foreach ($sdgTags as $sdgNumber) {
                        $stmt->execute([$postId, $sdgNumber]);
                    }
                }
            }
            
            // Commit transaction
            $pdo->commit();
            error_log("Transaction committed successfully");
            
            // Set success message and redirect to post management
            if ($isEdit) {
                $successMsg = urlencode('Post Updated Successfully');
            } else {
                $successMsg = urlencode('Post created successfully!');
            }
            
            // Redirect to post management with success message
            header('Location: posts.php?success=' . $successMsg);
            exit;
            
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Exception in post creation: " . $e->getMessage());
            $error = $e->getMessage();
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("PDO Exception in post creation: " . $e->getMessage());
            $error = 'Failed to create post. Please try again.';
            }
            }
        }
    }
}
?>

<?php 
// For create-post, we need to include editor.css as well
$additional_css = '<link rel="stylesheet" href="../assets/css/editor.css">';
?>
<?php include '../app/includes/admin-header.php'; ?>

    <!-- Editor Content -->
    <div class="editor-container">
        <div class="editor-header">
            <h1 class="editor-title">
                <i class="fas fa-edit"></i>
                <?php echo $isEdit ? 'Edit Post' : 'Create New Post'; ?>
            </h1>
            <p class="editor-subtitle"><?php echo $isEdit ? 'Update your post content' : 'Share your thoughts with the world'; ?></p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="editor-form" enctype="multipart/form-data">
            <?php echo CSRF::field(); ?>
            <?php if ($isEdit): ?>
                <input type="hidden" name="is_edit" value="1">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="title" class="form-label">
                    <i class="fas fa-heading"></i>
                    Post Title
                </label>
                <input type="text" id="title" name="title" class="form-input" 
                       value="<?php echo $isEdit ? htmlspecialchars($post['title']) : (isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''); ?>" 
                       placeholder="Enter your post title..." required>
            </div>

            <div class="form-group">
                <label for="category" class="form-label">
                    <i class="fas fa-tags"></i>
                    Category (Program/Academics)
                </label>
                <select id="category" name="category" class="form-input">
                    <option value="">Select a Category (Optional)</option>
                    <?php
                    // Get the current category ID for edit mode
                    $currentCategoryId = null;
                    if ($isEdit && isset($post['category_id']) && !empty($post['category_id'])) {
                        $currentCategoryId = (int)$post['category_id'];
                    } elseif (isset($_POST['category']) && is_numeric($_POST['category'])) {
                        $currentCategoryId = (int)$_POST['category'];
                    }
                    ?>
                    <?php if (!empty($programCategories)): ?>
                        <optgroup label="Programs">
                            <?php foreach ($programCategories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($currentCategoryId === (int)$cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endif; ?>
                    <?php if (!empty($academicsCategories)): ?>
                        <optgroup label="Academics">
                            <?php foreach ($academicsCategories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($currentCategoryId === (int)$cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endif; ?>
                </select>
                <small class="form-help">Select the program or academics section this post relates to. Posts will be displayed on their respective pages.</small>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-globe"></i>
                    SDG Tags (Optional)
                </label>
                <div class="sdg-tags-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 6px; margin-top: 8px;">
                    <?php
                    // Get selected SDG tags
                    $selectedSdgTags = [];
                    if ($isEdit && isset($existingSdgTags)) {
                        $selectedSdgTags = $existingSdgTags;
                    } elseif (isset($_POST['sdg_tags']) && is_array($_POST['sdg_tags'])) {
                        $selectedSdgTags = array_map('intval', $_POST['sdg_tags']);
                    }
                    
                    foreach ($sdgGoals as $number => $title): 
                        $isChecked = in_array($number, $selectedSdgTags);
                    ?>
                        <label class="sdg-tag-checkbox" style="display: flex; align-items: center; padding: 4px 8px; border: 1px solid #e5e7eb; border-radius: 4px; cursor: pointer; transition: all 0.2s; background: <?php echo $isChecked ? '#eff6ff' : '#fff'; ?>; border-color: <?php echo $isChecked ? '#2563eb' : '#e5e7eb'; ?>; font-size: 0.8rem;">
                            <input type="checkbox" name="sdg_tags[]" value="<?php echo $number; ?>" 
                                   <?php echo $isChecked ? 'checked' : ''; ?>
                                   style="margin-right: 6px; cursor: pointer; width: 14px; height: 14px;"
                                   onchange="this.parentElement.style.background = this.checked ? '#eff6ff' : '#fff'; this.parentElement.style.borderColor = this.checked ? '#2563eb' : '#e5e7eb';">
                            <span style="font-size: 0.75rem; font-weight: 500; line-height: 1.2;">
                                <strong>SDG <?php echo $number; ?>:</strong> <span style="font-size: 0.7rem;"><?php echo htmlspecialchars($title); ?></span>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <small class="form-help" style="display: block; margin-top: 6px; color: #6b7280; font-size: 0.8rem;">
                    <i class="fas fa-info-circle"></i> Select one or more SDG goals to tag this post. Tagged posts will appear in the corresponding SDG modal on the SDG Initiatives page.
                </small>
            </div>

            <div class="form-group">
                <label for="excerpt" class="form-label">
                    <i class="fas fa-quote-left"></i>
                    Excerpt (Optional)
                </label>
                <textarea id="excerpt" name="excerpt" class="form-textarea" rows="3"
                          placeholder="Write a brief summary of your post..."><?php echo $isEdit ? htmlspecialchars($post['excerpt']) : (isset($_POST['excerpt']) ? htmlspecialchars($_POST['excerpt']) : ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="content" class="form-label">
                    <i class="fas fa-align-left"></i>
                    Content
                </label>
                <!-- Quill Editor Container -->
                <div id="content-editor"></div>
                <!-- Hidden textarea for form submission -->
                <textarea id="content" name="content" class="form-textarea" required><?php 
                    // For Quill, we need to decode HTML entities to show the actual HTML
                    if ($isEdit && isset($post['content'])) {
                        // Decode HTML entities so Quill can properly display the formatted content
                        echo html_entity_decode($post['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    } elseif (isset($_POST['content'])) {
                        echo html_entity_decode($_POST['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }
                ?></textarea>
                <small class="form-help" style="display: block; margin-top: 8px; color: #6b7280; font-size: 0.875rem;">
                    <i class="fas fa-info-circle"></i> Use the formatting toolbar above to add <strong>bold</strong>, <em>italic</em>, and other text formatting.
                </small>
            </div>

            <div class="form-group">
                <label for="images" class="form-label">
                    <i class="fas fa-images"></i>
                    Attach Images <span style="color: red;">*</span>
                </label>
                <div class="image-upload-container">
                    <input type="file" id="images" name="images[]" class="image-input" 
                           multiple accept="image/*" accept="image/jpeg,image/png,image/gif,image/webp" <?php echo !$isEdit ? 'required' : ''; ?>>
                    <div class="image-upload-area" onclick="document.getElementById('images').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Click to select images or drag and drop</p>
                        <small>Supported formats: JPEG, PNG, GIF, WebP (Max 10MB each). <strong>At least one image is required.</strong></small>
                    </div>
                    <?php
                    // Prepare existing images once for edit mode
                    $existingImages = [];
                    if ($isEdit && $post) {
                        $stmt = $pdo->prepare("SELECT * FROM post_images WHERE post_id = ? ORDER BY sort_order ASC, created_at ASC");
                        $stmt->execute([$post['id']]);
                        $existingImages = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }
                    ?>
                    <div id="image-preview" class="image-preview">
                        <?php if ($isEdit && $post && !empty($existingImages)): ?>
                            <?php foreach ($existingImages as $index => $image): ?>
                                <?php 
                                // Format image path for admin directory (go up one level to root)
                                $imgPath = ltrim($image['image_path'], '/');
                                $imgSrc = '../' . $imgPath;
                                $imageId = (int)$image['id'];
                                ?>
                                <div class="image-preview-item existing-image" data-image-id="<?php echo $imageId; ?>" style="position: relative;">
                                    <img src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>" 
                                         alt="Existing image"
                                         style="position: relative; z-index: 1;"
                                         onerror="this.style.display='none';">
                                    <button type="button" 
                                            class="remove-image" 
                                            data-image-id="<?php echo $imageId; ?>"
                                            onclick="removeExistingImage(<?php echo $imageId; ?>); return false;" 
                                            title="Remove this image"
                                            style="position: absolute !important; top: 6px !important; right: 6px !important; background: rgba(239, 68, 68, 0.95) !important; color: white !important; border: none !important; border-radius: 50% !important; width: 22px !important; height: 22px !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important; font-size: 12px !important; z-index: 9999 !important; opacity: 1 !important; visibility: visible !important; pointer-events: auto !important;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <div class="image-info">
                                        <?php echo htmlspecialchars(basename($imgPath), ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php elseif ($isEdit && $post && !empty($post['featured_image'])): ?>
                            <?php
                            // Fallback: show featured_image if no post_images exist
                            $img = ltrim($post['featured_image'], '/');
                            $imgSrc = '../' . $img;
                            // Create a special ID for featured_image removal
                            $featuredImageId = 'featured_' . $post['id'];
                            ?>
                            <div class="image-preview-item existing-image" data-featured-image="1" data-image-id="<?php echo $featuredImageId; ?>" style="position: relative;">
                                <img src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>" 
                                     alt="Existing image"
                                     style="position: relative; z-index: 1;"
                                     onerror="this.style.display='none';">
                                <button type="button" 
                                        class="remove-image" 
                                        data-image-id="<?php echo $featuredImageId; ?>"
                                        onclick="removeFeaturedImage(<?php echo $post['id']; ?>); return false;" 
                                        title="Remove this image"
                                        style="position: absolute !important; top: 6px !important; right: 6px !important; background: rgba(239, 68, 68, 0.95) !important; color: white !important; border: none !important; border-radius: 50% !important; width: 22px !important; height: 22px !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important; font-size: 12px !important; z-index: 9999 !important; opacity: 1 !important; visibility: visible !important; pointer-events: auto !important;">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div class="image-info">
                                    <?php echo htmlspecialchars(basename($img), ENT_QUOTES, 'UTF-8'); ?> (Featured Image)
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="status" class="form-label">
                        <i class="fas fa-eye"></i>
                        Status
                    </label>
                    <select id="status" name="status" class="form-input">
                        <?php 
                        // Use default post status for new posts, or existing status for edits
                        $defaultStatus = $isEdit ? ($post['status'] ?? 'draft') : getSetting('default_post_status', 'draft');
                        $selectedStatus = isset($_POST['status']) ? $_POST['status'] : ($isEdit ? ($post['status'] ?? 'draft') : $defaultStatus);
                        ?>
                        <option value="draft" <?php echo $selectedStatus === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="published" <?php echo $selectedStatus === 'published' ? 'selected' : ''; ?>>Published</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="published_date" class="form-label">
                        <i class="fas fa-calendar"></i>
                        Publish Date
                    </label>
                    <input type="datetime-local" id="published_date" name="published_date" class="form-input"
                           value="<?php 
                               if ($isEdit && $post['published_at']) {
                                   echo date('Y-m-d\TH:i', strtotime($post['published_at']));
                               } elseif (isset($_POST['published_date'])) {
                                   echo htmlspecialchars($_POST['published_date']);
                               } else {
                                   echo date('Y-m-d\TH:i');
                               }
                           ?>">
                </div>
            </div>

            <div class="form-actions">
                <a href="posts.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <?php echo $isEdit ? 'Update Post' : 'Create Post'; ?>
                </button>
            </div>
        </form>
    </div>

        <script src="../assets/js/script.js"></script>
    <!-- Quill Rich Text Editor (Free, No API Key Required) -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <style>
        #content-editor {
            height: 400px;
            margin-bottom: 20px;
        }
        .ql-editor {
            min-height: 350px;
            font-family: -apple-system, BlinkMacSystemFont, 'San Francisco', 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            font-size: 14px;
        }
        /* Normalize paragraph spacing in Quill editor */
        .ql-editor p {
            margin-bottom: 1rem;
            margin-top: 0;
        }
        .ql-editor p:empty {
            display: none;
            margin: 0;
            padding: 0;
        }
        /* Hide the original textarea, we'll sync with it */
        #content {
            display: none;
        }
    </style>
    <script>
        // Initialize Quill Editor when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill Editor
            var quill = new Quill('#content-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        ['link', 'blockquote', 'code-block'],
                        ['clean']
                    ]
                },
                placeholder: 'Write your post content here...'
            });

            // Get initial content from textarea
            var textarea = document.getElementById('content');
            if (textarea && textarea.value) {
                quill.root.innerHTML = textarea.value;
            }

            // Function to normalize text spacing and remove irregular whitespace
            function normalizeTextSpacing(text) {
                // Replace non-breaking spaces (and other unicode spaces) with regular spaces
                text = text.replace(/[\u00A0\u2000-\u200B\u202F\u205F\u3000]/g, ' ');
                // Replace tabs with spaces
                text = text.replace(/\t/g, ' ');
                // Replace single line breaks (not double line breaks) with spaces
                // This converts line breaks within sentences to spaces
                text = text.replace(/([^\n])\n([^\n])/g, '$1 $2');
                // Replace multiple consecutive spaces with single space
                text = text.replace(/[ ]{2,}/g, ' ');
                // Remove spaces at start/end
                text = text.trim();
                return text;
            }

            // Function to normalize paragraph spacing in HTML
            function normalizeParagraphSpacing(html) {
                // Replace non-breaking spaces with regular spaces
                html = html.replace(/\u00A0/g, ' ');
                // Remove empty paragraphs
                html = html.replace(/<p[^>]*>\s*<\/p>/gi, '');
                // Remove multiple consecutive <br> tags
                html = html.replace(/(<br\s*\/?>){2,}/gi, '<br>');
                // Remove <br> tags at the start or end of paragraphs
                html = html.replace(/<p[^>]*>(\s*<br\s*\/?>\s*)+/gi, '<p>');
                html = html.replace(/(\s*<br\s*\/?>\s*)+<\/p>/gi, '</p>');
                // Normalize whitespace in text nodes - replace multiple spaces with single space
                // BUT preserve single spaces (don't trim them)
                html = html.replace(/(?<=>)([^<]+)(?=<)/g, function(match) {
                    // Normalize all types of whitespace
                    match = match.replace(/[\u00A0\u2000-\u200B\u202F\u205F\u3000]/g, ' '); // Unicode spaces
                    match = match.replace(/\t/g, ' '); // Tabs
                    match = match.replace(/[ ]{2,}/g, ' '); // Multiple spaces to single space
                    // DON'T trim - preserve leading/trailing single spaces
                    return match;
                });
                // Normalize whitespace in paragraphs - only remove excessive whitespace
                html = html.replace(/<p[^>]*>(\s{2,})/gi, '<p>');
                html = html.replace(/(\s{2,})<\/p>/gi, '</p>');
                return html;
            }

            // Simple paste handler - intercept paste, normalize, and insert clean text
            quill.root.addEventListener('paste', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Get plain text from clipboard
                var clipboardData = e.clipboardData || window.clipboardData;
                var pastedText = clipboardData.getData('text/plain');
                
                if (pastedText) {
                    // Normalize spacing - remove extra spaces
                    pastedText = normalizeTextSpacing(pastedText);
                    
                    // Get current selection
                    var selection = quill.getSelection(true);
                    if (!selection) {
                        selection = { index: quill.getLength(), length: 0 };
                    }
                    
                    // Delete selected text if any
                    if (selection.length > 0) {
                        quill.deleteText(selection.index, selection.length, 'user');
                    }
                    
                    // Insert normalized text as plain text (no formatting)
                    // Use null format to ensure it uses default editor formatting
                    quill.insertText(selection.index, pastedText, null, 'user');
                    
                    // Remove any formatting that might have been applied
                    quill.formatText(selection.index, pastedText.length, {
                        color: false,
                        background: false,
                        font: false,
                        size: false
                    }, 'user');
                    
                    // Move cursor to end of inserted text
                    quill.setSelection(selection.index + pastedText.length, 0, 'user');
                    
                    // Sync to textarea
                    textarea.value = quill.root.innerHTML;
                }
            });

            // Sync Quill content to textarea before form submission
            var form = document.querySelector('.editor-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Get HTML content from Quill
                    var htmlContent = quill.root.innerHTML;
                    // Normalize paragraph spacing
                    htmlContent = normalizeParagraphSpacing(htmlContent);
                    // Update the hidden textarea with the normalized HTML content
                    textarea.value = htmlContent;
                });
            }

            // Sync on content change
            quill.on('text-change', function() {
                textarea.value = quill.root.innerHTML;
            });
        });
    </script>
    <script>
        // Form validation - ensure images are provided
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.editor-form');
            const imageInput = document.getElementById('images');
            const imagePreview = document.getElementById('image-preview');
            const isEdit = <?php echo $isEdit ? 'true' : 'false'; ?>;
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    const hasExistingImages = imagePreview && imagePreview.querySelectorAll('.existing-image').length > 0;
                    const hasNewImages = imageInput && imageInput.files && imageInput.files.length > 0;
                    
                    if (!hasExistingImages && !hasNewImages) {
                        e.preventDefault();
                        alert('Please attach at least one image. Images are required for posts.');
                        imageInput.focus();
                        return false;
                    }
                });
            }
        });
        
        // Image upload and preview functionality
        function initImageUpload() {
            const imageInput = document.getElementById('images');
            const previewContainer = document.getElementById('image-preview');
            
            if (!imageInput || !previewContainer) {
                return;
            }
            
            imageInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                
                // Preserve existing images - store them before any manipulation
                const existingImages = Array.from(previewContainer.querySelectorAll('.existing-image'));
                
                // Clear only new previews (not existing images)
                const newPreviews = previewContainer.querySelectorAll('.image-preview-item:not(.existing-image)');
                newPreviews.forEach(preview => preview.remove());
                
                if (files.length === 0) {
                    return;
                }
                
                files.forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewItem = document.createElement('div');
                            previewItem.className = 'image-preview-item';
                            previewItem.innerHTML = `
                                <img src="${e.target.result}" alt="Preview">
                                <button type="button" class="remove-image" onclick="removeImage(${index})">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div class="image-info">
                                    ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)
                                </div>
                            `;
                            previewContainer.appendChild(previewItem);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });
        }
        
        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initImageUpload);
        } else {
            initImageUpload();
        }
        
        // Drag and drop functionality
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.querySelector('.image-upload-area');
            if (uploadArea) {
                uploadArea.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    uploadArea.style.borderColor = '#2563eb';
                    uploadArea.style.background = '#eff6ff';
                });
                
                uploadArea.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    uploadArea.style.borderColor = '#cbd5e1';
                    uploadArea.style.background = '#f8fafc';
                });
                
                uploadArea.addEventListener('drop', function(e) {
                    e.preventDefault();
                    uploadArea.style.borderColor = '#cbd5e1';
                    uploadArea.style.background = '#f8fafc';
                    
                    const files = Array.from(e.dataTransfer.files);
                    const fileInput = document.getElementById('images');
                    
                    if (fileInput) {
                        // Create a new FileList
                        const dt = new DataTransfer();
                        files.forEach(file => dt.items.add(file));
                        fileInput.files = dt.files;
                        
                        // Trigger change event
                        fileInput.dispatchEvent(new Event('change'));
                    }
                });
            }
        });
        
        function removeImage(index) {
            const fileInput = document.getElementById('images');
            const dt = new DataTransfer();
            
            // Recreate FileList without the removed file
            Array.from(fileInput.files).forEach((file, i) => {
                if (i !== index) {
                    dt.items.add(file);
                }
            });
            
            fileInput.files = dt.files;
            
            // Refresh preview
            fileInput.dispatchEvent(new Event('change'));
        }
        
        // Ensure all existing images have remove buttons on page load
        function ensureRemoveButtons() {
            // Find ALL existing-image containers, not just those with data-image-id
            const allExistingImages = document.querySelectorAll('.existing-image');
            
            allExistingImages.forEach(function(imgContainer, index) {
                const imageId = imgContainer.getAttribute('data-image-id');
                const isFeatured = imgContainer.hasAttribute('data-featured-image');
                
                // Check if remove button already exists
                let removeBtn = imgContainer.querySelector('.remove-image');
                
                // Add button if it has an imageId (including featured_image with special ID)
                if (!removeBtn && imageId) {
                    // Create remove button if it doesn't exist
                    removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'remove-image';
                    removeBtn.title = 'Remove this image';
                    removeBtn.setAttribute('data-image-id', imageId);
                    removeBtn.style.cssText = 'position: absolute !important; top: 6px !important; right: 6px !important; background: rgba(239, 68, 68, 0.95) !important; color: white !important; border: none !important; border-radius: 50% !important; width: 22px !important; height: 22px !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important; font-size: 12px !important; z-index: 9999 !important; opacity: 1 !important; visibility: visible !important; pointer-events: auto !important;';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    // Check if this is a featured image (starts with 'featured_')
                    if (imageId && imageId.toString().startsWith('featured_')) {
                        const postId = parseInt(imageId.toString().replace('featured_', ''));
                        removeBtn.onclick = function(e) { 
                            e.preventDefault();
                            e.stopPropagation();
                            removeFeaturedImage(postId); 
                        };
                    } else {
                        removeBtn.onclick = function(e) { 
                            e.preventDefault();
                            e.stopPropagation();
                            removeExistingImage(parseInt(imageId)); 
                        };
                    }
                    imgContainer.style.position = 'relative';
                    imgContainer.appendChild(removeBtn);
                } else if (removeBtn) {
                    // Force visibility with maximum z-index
                    removeBtn.style.cssText += 'display: flex !important; visibility: visible !important; opacity: 1 !important; z-index: 9999 !important; pointer-events: auto !important;';
                    removeBtn.style.display = 'flex';
                    removeBtn.style.visibility = 'visible';
                    removeBtn.style.opacity = '1';
                    removeBtn.style.zIndex = '9999';
                    removeBtn.style.pointerEvents = 'auto';
                }
            });
        }
        
        // Run on DOM ready and also after a short delay to catch any late-loading elements
        document.addEventListener('DOMContentLoaded', ensureRemoveButtons);
        setTimeout(ensureRemoveButtons, 100);
        setTimeout(ensureRemoveButtons, 500);
        
        // Handle removal of featured_image (when no post_images exist)
        function removeFeaturedImage(postId) {
            const imagePreview = document.getElementById('image-preview');
            const imageInput = document.getElementById('images');
            const newImages = imageInput && imageInput.files && imageInput.files.length > 0;
            
            if (!newImages) {
                alert('Please upload at least one new image before removing the featured image. At least one image is required for posts.');
                return;
            }
            
            if (confirm('Are you sure you want to remove this featured image? Make sure you have uploaded new images first.')) {
                // Create a hidden input to mark featured_image for clearing
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'clear_featured_image';
                deleteInput.value = '1';
                document.querySelector('form').appendChild(deleteInput);
                
                // Remove the preview item
                const featuredImageContainer = imagePreview.querySelector('[data-featured-image="1"]');
                if (featuredImageContainer) {
                    featuredImageContainer.remove();
                }
            }
        }
        
        function removeExistingImage(imageId) {
            const imagePreview = document.getElementById('image-preview');
            const imageInput = document.getElementById('images');
            const existingImages = imagePreview.querySelectorAll('.existing-image:not([data-featured-image="1"])');
            const featuredImage = imagePreview.querySelector('[data-featured-image="1"]');
            const newImages = imageInput && imageInput.files && imageInput.files.length > 0;
            
            // Check if removing this image would leave no images
            // Count only actual post_images (not featured_image fallback)
            const remainingImages = existingImages.length - 1; // -1 because we're removing one
            const hasFeaturedFallback = featuredImage !== null;
            
            if (remainingImages === 0 && !hasFeaturedFallback && !newImages) {
                alert('Cannot remove the last image. At least one image is required for posts. Please upload a new image before removing this one.');
                return;
            }
            
            if (confirm('Are you sure you want to remove this image?')) {
                // Create a hidden input to mark this image for deletion
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete_images[]';
                deleteInput.value = imageId;
                document.querySelector('form').appendChild(deleteInput);
                
                // Remove the preview item
                const previewItem = event.target.closest('.image-preview-item');
                if (previewItem) {
                    previewItem.remove();
                }
            }
        }
    </script>

<?php include '../app/includes/admin-footer.php'; ?>



