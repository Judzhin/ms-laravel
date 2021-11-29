<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Spatie\YamlFrontMatter\Document;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class Post
 * @package App\Models
 */
class Post
{
    /**
     * Post constructor.
     * @param string $title
     * @param string $slug
     * @param string $excerpt
     * @param string $date
     * @param string $body
     */
    public function __construct(
        public string $title,
        public string $slug,
        public string $excerpt,
        public string $date,
        public string $body
    )
    {
    }

    /**
     * @param Document $document
     * @return static
     */
    public static function parseFromDocument(Document $document): self
    {
        return new self(
            $document->title,
            $document->slug,
            $document->excerpt,
            $document->date,
            $document->body(),
        );
    }

    /**
     * @return Collection
     * @throws \Exception
     */
    public static function all(): Collection
    {
        return cache()->rememberForever('posts.all', function () {
            $files = File::files(resource_path("posts"));
            return collect($files)
                ->map(fn(SplFileInfo $file) => YamlFrontMatter::parseFile($file))
                ->map(fn(Document $document) => Post::parseFromDocument($document))
                ->sortByDesc('date');
        });

        // =======================================================================
        // $files = File::files(resource_path("posts"));
        //
        // return collect($files)
        //     ->map(fn(SplFileInfo $file) => YamlFrontMatter::parseFile($file))
        //     ->map(fn(Document $document) => Post::parseFromDocument($document))
        //     ->sortByDesc('date');

        ////return array_map(function (SplFileInfo $file) {
        ////    return $file->getContents();
        ////}, File::files(resource_path("posts/")));
        //
        //$files = File::files(resource_path("posts/"));
        //
        //foreach ($files as $k => $file) {
        //    // $files[$k] = YamlFrontMatter::parseFile($file);
        //    $files[$k] = YamlFrontMatter::parseFile($file);
        //}
        //
        //// ddd($files);
        //
        //return array_map(fn(SplFileInfo $file) => $file->getContents(), $files);
    }

    /**
     * @param string $slug
     * @return Post
     */
    public static function findBySlug(string $slug): Post
    {
        $posts = static::all();
        // dd($posts->firstWhere('slug', $slug));
        return $posts->firstWhere('slug', $slug);
    }

    /**
     * @param $id
     * @param $slug
     * @return mixed
     * @throws \Exception
     */
    public static function findByIdAndSlug($id, $slug)
    {
        // if (!file_exists($filename = __DIR__ . "/../resources/posts/{$slug}.html")) {
        if (!file_exists($filename = resource_path("posts/{$slug}.html"))) {
            // ddd('file does not exists');
            // abort(\Illuminate\Http\Response::HTTP_NOT_FOUND);
            // return redirect('/');
            throw new ModelNotFoundException;
        }

        // $post = file_get_contents($filename);
        //$post = cache()->remember("posts.{$id}-{$slug}", now()->addSeconds(1200), function() use ($filename) {
        //    // var_dump('file_get_contents');
        //    return file_get_contents($filename);
        //});

        return cache()->remember("posts.{$id}-{$slug}", now()->addSeconds(1200), fn() => file_get_contents($filename));
    }
}
