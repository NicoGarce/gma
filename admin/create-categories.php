<?php
/**
 * UPHSL Admin Create Categories Script
 * 
 * This script creates all missing categories from the navbar configuration
 * Run this once to populate all categories in the database
 */

require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if user is logged in and has appropriate role
if (!isLoggedIn() || (!isAdmin() && !isSuperAdmin())) {
    die('Access denied. Admin or Super Admin access required.');
}

$pdo = getDBConnection();

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

// Get all existing categories
$allCategories = getAllCategories();
$existingCategoriesMap = [];
foreach ($allCategories as $category) {
    $existingCategoriesMap[$category['name']] = $category;
}

// Get all category names from navbar config
$programNames = array_values($navbar_items_config['programs']);
$academicsNames = array_values($navbar_items_config['academics']);
$allNavbarCategoryNames = array_merge($programNames, $academicsNames);

$created = 0;
$skipped = 0;
$errors = [];

echo "<h2>Creating Categories</h2>";
echo "<ul>";

foreach ($allNavbarCategoryNames as $categoryName) {
    if (isset($existingCategoriesMap[$categoryName])) {
        echo "<li style='color: #666;'>Skipped: <strong>{$categoryName}</strong> (already exists)</li>";
        $skipped++;
    } else {
        try {
            // Generate slug from category name
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $categoryName)));
            $slug = preg_replace('/-+/', '-', $slug); // Replace multiple dashes with single dash
            $slug = trim($slug, '-'); // Remove leading/trailing dashes
            
            // Create new category
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
            $stmt->execute([$categoryName, $slug]);
            echo "<li style='color: green;'>Created: <strong>{$categoryName}</strong> (slug: {$slug})</li>";
            $created++;
        } catch (PDOException $e) {
            echo "<li style='color: red;'>Error creating <strong>{$categoryName}</strong>: " . htmlspecialchars($e->getMessage()) . "</li>";
            $errors[] = $categoryName;
        }
    }
}

echo "</ul>";
echo "<hr>";
echo "<p><strong>Summary:</strong></p>";
echo "<ul>";
echo "<li>Created: <strong>{$created}</strong> categories</li>";
echo "<li>Skipped: <strong>{$skipped}</strong> categories (already exist)</li>";
if (!empty($errors)) {
    echo "<li style='color: red;'>Errors: <strong>" . count($errors) . "</strong> categories failed to create</li>";
}
echo "</ul>";

if ($created > 0) {
    echo "<p style='color: green;'><strong>Categories created successfully! You can now refresh the create post page.</strong></p>";
}

echo "<p><a href='create-post.php'>Go to Create Post Page</a> | <a href='posts.php'>Go to Post Management</a></p>";
?>

