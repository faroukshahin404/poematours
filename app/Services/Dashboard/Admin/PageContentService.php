<?php

namespace App\Services\Dashboard\Admin;

use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Support\Facades\DB;

class PageContentService
{
    /**
     * @param  array{
     *   meta_title: string|null,
     *   meta_description: string|null,
     *   meta_keywords: array<int, string>,
     *   og_tags: array<string, mixed>,
     *   body: string|null,
     *   show_in_footer: bool,
     *   footer_label: string|null,
     *   footer_sort_order: int|null
     * }  $payload
     */
    public function updatePage(Page $page, array $payload): void
    {
        $page->meta_title = $payload['meta_title'];
        $page->meta_description = $payload['meta_description'];
        $page->meta_keywords = $payload['meta_keywords'];
        $page->og_tags = $payload['og_tags'];
        $page->body = $payload['body'];
        $page->show_in_footer = $payload['show_in_footer'];
        $page->footer_label = $payload['footer_label'];
        $page->footer_sort_order = $payload['footer_sort_order'];
        $page->save();
    }

    /**
     * @param  array{
     *   name: string,
     *   slug: string,
     *   meta_title: string|null,
     *   meta_description: string|null,
     *   meta_keywords: array<int, string>,
     *   og_tags: array<string, mixed>,
     *   body: string|null,
     *   show_in_footer: bool,
     *   footer_label: string|null,
     *   footer_sort_order: int|null
     * }  $payload
     */
    public function createPage(array $payload): Page
    {
        $page = new Page;
        $page->name = $payload['name'];
        $page->slug = $payload['slug'];
        $page->meta_title = $payload['meta_title'];
        $page->meta_description = $payload['meta_description'];
        $page->meta_keywords = $payload['meta_keywords'];
        $page->og_tags = $payload['og_tags'];
        $page->body = $payload['body'];
        $page->show_in_footer = $payload['show_in_footer'];
        $page->footer_label = $payload['footer_label'];
        $page->footer_sort_order = $payload['footer_sort_order'];
        $page->save();

        return $page;
    }

    /**
     * @param  array{order: int, is_active: bool, content: array<string, mixed>}  $payload
     */
    public function updateSection(PageSection $section, array $payload): void
    {
        DB::transaction(function () use ($section, $payload): void {
            $section->order = $payload['order'];
            $section->is_active = $payload['is_active'];
            $section->content = $payload['content'];
            $section->save();
        });
    }
}
