<?php

use App\Models\Post;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Spatie\YamlFrontMatter\Document;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Symfony\Component\Finder\SplFileInfo;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return ['foo' => 'bar'];
    // return "Hello World";

    // ========================================================================
    //$files = File::files(resource_path("posts"));
    //
    //$posts = collect($files)
    //    ->map(fn(SplFileInfo $file) => YamlFrontMatter::parseFile($file))
    //    ->map(fn(Document $document) => Post::parseFromDocument($document));

    // ========================================================================
    //$posts = collect($files)
    //    ->map(function (SplFileInfo $file) {
    //        return YamlFrontMatter::parseFile($file);
    //    })
    //    ->map(function (Document $document) {
    //    return Post::parseFromDocument($document);
    //});

    // ========================================================================
    //$posts = array_map(function (SplFileInfo $file) {
    //    $document = YamlFrontMatter::parseFile($file);
    //    return Post::parseFromDocument($document);
    //}, $files);

    // ========================================================================
    //$posts = [];
    //foreach ($files as $k => $file) {
    //    $document = YamlFrontMatter::parseFile($file);
    //    // ddd($document);
    //    $posts[] = \App\Models\Post::parseFromDocument($document);
    //    // $posts[$k] = new \App\Models\Post(
    //    //     $document->title,
    //    //     $document->excerpt,
    //    //     $document->date,
    //    //     $document->body(),
    //    // );
    //}

    // ddd($posts);

    // ========================================================================
    //$post = \Spatie\YamlFrontMatter\YamlFrontMatter::parseFile(
    //    resource_path('posts/first-post.html')
    //);
    //
    // ddd($posts);

    /** @var array $posts */
    $posts = Post::all();

    return view('posts', [
        'posts' => $posts
    ]);
});

Route::get('/posts/{id}-{slug}.html', function ($id, $slug) {
    // ddd(func_get_args());

    //if (!file_exists($filename = __DIR__ . "/../resources/posts/{$slug}.html")) {
    //    // ddd('file does not exists');
    //    // abort(\Illuminate\Http\Response::HTTP_NOT_FOUND);
    //    return redirect('/');
    //}
    //
    //// $post = file_get_contents($filename);
    ////$post = cache()->remember("posts.{$id}-{$slug}", now()->addSeconds(1200), function() use ($filename) {
    ////    // var_dump('file_get_contents');
    ////    return file_get_contents($filename);
    ////});
    //
    //$post = cache()->remember("posts.{$id}-{$slug}", now()->addSeconds(1200), fn() => file_get_contents($filename));

    return view('post', [
        // 'post' => '<p>Hello World!</p>'
        // 'post' => file_get_contents(__DIR__ . '/../resources/posts/post.html')
        // 'post' => file_get_contents($filename)
        // 'post' => $post
        // 'post' => Post::findByIdAndSlug($id, $slug)
        'post' => Post::findBySlug($slug)
    ]);
})->whereNumber('id')->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');
//->where([
//    'id' => '\d+',
//    'slug' => '[a-z0-9]+(?:-[a-z0-9]+)*'
//]);

