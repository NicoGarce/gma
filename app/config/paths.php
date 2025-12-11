<?php
/**
 * Path Configuration
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Automatic path detection for development and production environments
 */

// Detect if we're in development (XAMPP) or production (cPanel)
function getBasePath() {
    // Check if base_path was already set (e.g., by a page in a subdirectory)
    if (isset($GLOBALS['base_path']) && !empty($GLOBALS['base_path'])) {
        return $GLOBALS['base_path'];
    }
    
    // Get the current script's file system path
    $scriptFile = $_SERVER['SCRIPT_FILENAME'];
    $scriptDir = dirname($scriptFile);
    
    // Walk up the directory tree to find the project root
    // Project root is identified by the presence of 'assets' and 'app' directories
    $currentDir = $scriptDir;
    $maxDepth = 10; // Prevent infinite loops
    $depth = 0;
    
    while ($depth < $maxDepth) {
        // Check if this directory contains both 'assets' and 'app' folders
        if (is_dir($currentDir . '/assets') && is_dir($currentDir . '/app')) {
            // Found project root - now calculate the web path
            $projectRoot = $currentDir;
            break;
        }
        
        // Move up one directory
        $parentDir = dirname($currentDir);
        if ($parentDir === $currentDir) {
            // Reached filesystem root, stop
            break;
        }
        $currentDir = $parentDir;
        $depth++;
    }
    
    // If we found the project root, calculate the relative path
    if (isset($projectRoot)) {
        // Get the document root
        $docRoot = $_SERVER['DOCUMENT_ROOT'];
        
        // Calculate relative path from document root to project root
        $relativePath = str_replace($docRoot, '', $projectRoot);
        $relativePath = str_replace('\\', '/', $relativePath); // Normalize Windows paths
        $relativePath = trim($relativePath, '/');
        
        // If we're in the document root, return empty string
        if (empty($relativePath)) {
            return '';
        }
        
        // Return the path with leading and trailing slashes
        return '/' . $relativePath . '/';
    }
    
    // Fallback: Use script directory method (original behavior)
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    $scriptDir = rtrim($scriptDir, '/');
    
    if ($scriptDir === '' || $scriptDir === '/') {
        return '';
    }
    
    return $scriptDir . '/';
}

// Set the base path
$base_path = getBasePath();

// Alternative method: Check for specific development indicators
if (isset($_SERVER['HTTP_HOST'])) {
    $host = $_SERVER['HTTP_HOST'];
    
    // Development indicators
    if (strpos($host, 'localhost') !== false || 
        strpos($host, '127.0.0.1') !== false) {
        // In development, we already calculated the correct path above
        // Just ensure it's properly formatted
        if (empty($base_path)) {
            $base_path = '';
        }
    } else {
        // This is production - ensure we have the correct path
        // If base_path is empty or just '/', use '/'
        if (empty($base_path) || $base_path === '/') {
            $base_path = '/';
        }
    }
}

// Ensure base_path ends with a slash (unless it's empty)
if (!empty($base_path) && substr($base_path, -1) !== '/') {
    $base_path .= '/';
}

// Make base_path available globally
if (!isset($GLOBALS['base_path'])) {
    $GLOBALS['base_path'] = $base_path;
} else {
    // If it was already set (e.g., by contact.php), use that value
    $base_path = $GLOBALS['base_path'];
}
?>
