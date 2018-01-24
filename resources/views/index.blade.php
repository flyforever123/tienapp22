<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf_token" content="{ csrf_token() }" />

    <title>Simplify Meta Tag Optimization</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('css/uptown.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://use.fontawesome.com/517309d031.js"></script>
 
</head>
<body class="index">
    <main>
        <header>
            <h1>Simplify Meta Tag Optimization</h1>
            <h2>Simple App Help You Optimize Meta Tag</h2>
        </header>

        {{-- Products --}}
        <section style="margin-top: 4.4rem">
            <article>
                <div class="column four">
                    <div class="card">
                        <h3>Products</h3>
                        <p>You can Preview, Edit and Optimize all your products here</p>
                    </div>
                </div>
                <div class="column eight">
                    <div class="card">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Preview</th>
                                    <th>Edit</th>
                                    <th>Optimize</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->title }}</td>
                                        <td><a href="https://{{ $shop->shopify_domain }}/products/{{ $product->handle }}"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                                        <td><a href="https://{{ $shop->shopify_domain }}/admin/products/{{ $product->id }}"><i class="fa fa-cog" aria-hidden="true"></i></a></td>
                                        <td><a href="https://{{ $shop->shopify_domain }}/admin/apps/{{ config('shopify-app.api_key') }}/meta-tags?id={{ $product->id }}">Optimize</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>
        </section>

        {{-- Pages --}}
        <section>
            <article>
                <div class="column four">
                    <div class="card">
                        <h3>Pages</h3>
                        <p>You can Preview, Edit and Optimize all your pages here</p>
                    </div>
                </div>
                <div class="column eight">
                    <div class="card">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Preview</th>
                                    <th>Edit</th>
                                    <th>Optimize</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pages as $page)
                                    <tr>
                                        <td>{{ $page->title }}</td>
                                        <td><a href="https://{{ $shop->shopify_domain }}/pages/{{ $page->handle }}"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                                        <td><a href="https://{{ $shop->shopify_domain }}/admin/pages/{{ $page->id }}"><i class="fa fa-cog" aria-hidden="true"></i></a></td>
                                        <td><a href="https://{{ $shop->shopify_domain }}/admin/apps/{{ config('shopify-app.api_key') }}/meta-tags-page?id={{ $page->id }}">Optimize</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>
        </section>

        {{-- Blog Posts --}}
        <section>
            <article>
                <div class="column four">
                    <div class="card">
                        <h3>Blog Posts</h3>
                        <p>You can Preview, Edit and Optimize all your blog posts here</p>
                    </div>
                </div>
                <div class="column eight">
                    <div class="card">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Preview</th>
                                    <th>Edit</th>
                                    <th>Optimize</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($articles as $article)
                                    <?php 
                                        $article_id = $article->id;
                                        $blog_id = $article->blog_id;
                                        $blog = $shop->api()->request('GET', '/admin/blogs/' . $blog_id . '.json')->body->blog;
                                        $full_handle = 'blogs/' . $blog->handle . '/' . $article->handle;
                                        $blog_id = $blog->id;
                                    ?>
                                    <tr>
                                        <td>{{ $article->title }}</td>
                                        <td><a href="https://{{ $shop->shopify_domain }}/{{ $full_handle }}"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                                        <td><a href="https://{{ $shop->shopify_domain }}/admin/blogs/{{ $blog_id }}/articles/{{ $article->id }}"><i class="fa fa-cog" aria-hidden="true"></i></a></td>
                                        <td><a href="https://{{ $shop->shopify_domain }}/admin/apps/{{ config('shopify-app.api_key') }}/meta-tags?id={{ $article->id }}">Optimize</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>
        </section>
    
    </main>

</body>
</html>
