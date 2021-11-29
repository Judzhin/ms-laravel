<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class Post
 * @package App\Models
 */
class Post
{
    /**
     * @return void[]
     */
    public static function all()
    {
        //return array_map(function (SplFileInfo $file) {
        //    return $file->getContents();
        //}, File::files(resource_path("posts/")));

        return array_map(fn (SplFileInfo $file) => $file->getContents(), File::files(resource_path("posts/")));
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
