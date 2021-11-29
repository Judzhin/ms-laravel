<?php

use Illuminate\Support\Facades\Route;

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

    \Spatie\YamlFrontMatter\YamlFrontMatter::parseFile(
        resource_path('posts')
    );

    /** @var array $posts */
    $posts = \App\Models\Post::all();

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
        'post' => \App\Models\Post::findByIdAndSlug($id, $slug)
    ]);
})->whereNumber('id')->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');
//->where([
//    'id' => '\d+',
//    'slug' => '[a-z0-9]+(?:-[a-z0-9]+)*'
//]);

