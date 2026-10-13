# UI Component Guidelines

- **Form Select / Dropdown Menus**: Always reuse `<x-custom-select>` (`resources/views/components/custom-select.blade.php`) whenever building or updating select dropdowns in forms across the website (e.g., Province selection, category selection, or other options). Do not use browser-native `<select>` tags or standard navigation `<x-dropdown>`.
