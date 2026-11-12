<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Schema;

class LandingSearchBar extends Component
{

    public string $search = '';
    public string $selectedCategory = '';
    public array $categoryOptions = [];
    public ?string $activeCategoryI18nKey = null;
    public ?string $activeCategoryName = null;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->search = (string) request('search', '');
        $categories = $this->loadCategories();

        $this->selectedCategory = $this->resolveSelectedCategory($categories);
        $this->categoryOptions = $this->buildOption($categories);

        $activeCategories = $this->findActiveCategory($categories);


        $this->activeCategoryI18nKey = $activeCategories ? $this->translationKey($activeCategories->slug): null;
        $this->activeCategoryName = $this->resolveActiveCategoryName($activeCategories);

    }

    //* Helper method to load categories
    public function loadCategories(): Collection
    {
        return Schema::hasTable('categories')
            ? Category::orderBy('name')->get()
            : collect();
    }

    //* Helper method to find active category
    protected function findActiveCategory(Collection $categories): ?Category
    {
        $parameter = (string) request('category');

        if (blank($parameter)) {
            return null;
        }

        if (is_numeric($parameter)) {
            return $categories->firstWhere('id', (int) $parameter);
        }

        return $categories->first(
            fn($c) => $c->slug === $parameter || strcasecmp($c->name, (String) $parameter) === 0
        );
    }

    //* Helper method to resolve the activity category name
    protected function resolveActiveCategoryName(?Category $activeCategory): ?string
    {
        $parameter = request('category');
        if (blank($parameter)) {
            return null;
        }
        // Unknown category: fall back to the raw value, like the controller did
        if (!$activeCategory) {
            return ucfirst((string) $parameter);
        }

        $key = $this->translationKey($activeCategory->slug);

        return $key ? __($key) : $activeCategory->name;
    }

    //* Helper method for accept either slug or numeric id from the query to return the slug
    public function resolveSelectedCategory(Collection $categories): string
    {
        $selected = (string) request('category', '');

        if ($selected !== '' && is_numeric($selected)) {
            $founded = $categories->firstWhere('id', (int) $selected);
            if ($founded) {
                return $founded->slug;
            }
        }
        return $selected;
    }

    //* Helper method for option builds
    public function buildOption(Collection $categories): array
    {
        $all = [
            'label' => __('landing.all_categories'),
            'value' => '',
            'key' => 'landing.all_categories',
        ];
        if ($categories->isEmpty()) {
            return [
                $all,
                ['label' => __('landing.temples'), 'value' => 'temples', 'key' => 'landing.temples'],
                ['label' => __('landing.beaches'), 'value' => 'beaches', 'key' => 'landing.beaches'],
                ['label' => __('landing.nature'), 'value' => 'nature', 'key' => 'landing.nature'],
            ];
        }
        return array_merge(
            [$all],
            $categories->map(
                function ($c) {
                    $key = $this->translationKey($c->slug);
                    return [
                        'label' => $key ? __($key) : $c->name,
                        'value' => $c->slug,
                        'key' => $key,
                    ];
                }
            )->all()
        );
    }

    //* Helper method for key translation

    public function translationKey(string $slug): ?string
    {
        $key = "landing.{$slug}";
        return Lang::has($key) ? $key : null;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.landing-search-bar');
    }
}
