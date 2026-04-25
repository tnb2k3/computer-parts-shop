<?php

namespace App\Core;

class View
{
    /**
     * Render a view file with data
     */
    public static function render(string $viewPath, array $data = [], bool $useLayout = true): void
    {
        // Extract data to variables
        extract($data);

        // Start output buffering
        ob_start();

        // Include the view file
        $viewFile = __DIR__ . '/../Views/' . $viewPath . '.php';
        
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            throw new \Exception("View not found: $viewPath");
        }

        // Get the content
        $content = ob_get_clean();

        // If layout is needed, wrap content in layout
        if ($useLayout && !isset($data['_no_layout'])) {
            self::renderWithLayout($content, $data);
        } else {
            echo $content;
        }
    }

    /**
     * Render content with layout
     */
    private static function renderWithLayout(string $content, array $data = []): void
    {
        // Load categories for header mega-menu
        try {
            $categoryRepo = new \App\Repositories\CategoryRepository();
            $headerCategories = $categoryRepo->getAll();
        } catch (\Exception $e) {
            // If database isn't ready, use empty array
            $headerCategories = [];
        }
        
        // Add categories to data
        $data['headerCategories'] = $headerCategories;
        
        extract($data);
        
        $headerFile = __DIR__ . '/../Views/layout/header.php';
        $footerFile = __DIR__ . '/../Views/layout/footer.php';

        if (file_exists($headerFile)) {
            include $headerFile;
        }

        echo $content;

        if (file_exists($footerFile)) {
            include $footerFile;
        }
    }

    /**
     * Render without layout
     */
    public static function renderNoLayout(string $viewPath, array $data = []): void
    {
        $data['_no_layout'] = true;
        self::render($viewPath, $data, false);
    }
}
