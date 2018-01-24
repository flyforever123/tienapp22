<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf_token" content="{ csrf_token() }" />

    <title>Product Meta Tag Optimization</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('css/uptown.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loaders.min.css') }}">
    <script src="https://use.fontawesome.com/517309d031.js"></script>
</head>
<body>
    <input type="hidden" name="product_id" value="{{ $product_id }}">
    <main>
        <header>
            <h1>Simplify Meta Tag Optimization</h1>
            <h2>Simple App Help You Optimize Meta Tag Easily</h2>
        </header>
        <section>
            <article>
                <div class="card">
                    <div id="app" :class="['container']">
                        <div class="preloading" v-if="preloading">
                            <div class="ball-pulse"><div></div><div></div><div></div></div>
                        </div>
                        <!-- SEO Preview -->
                        <div class="preview">
                            <div class="message-header">
                                <h3>SERP Preview - See how it look in Google</h3>
                            </div>
                            <div class="message-body">
                                <div id="seo-preview">
                                    <div class="seo-title-preview">
                                        <span id="out_title">@{{ title }}</span>
                                    </div>
                                    <div class="seo-url-preview">
                                        <!--<span id="out_date"></span>-->
                                        <span id="out_url">@{{ shop_domain }}{{ $type }}/@{{ url }}</span> <!--<span id="out_dash1"></span>-->
                                    </div>
                                    <div class="seo-description-preview">
                                        <!--<span id="out_date"></span><span id="out_datedots">&nbsp;-&nbsp;</span>-->
                                        <span id="out_snippet">@{{ snippet }}</span>
                                    </div>
                                </div>
                            </div>
                        </div><!--//.seo-preview-->

                        <form>
                            <input type="hidden" name="product_id" value="{{ $product_id }}">
                            <div class="control">
                                <label for="keyword" class="label">Page Keyword:</label>
                                <input type="text" id="keyword" name="keyword" class="input" v-model="keyword" @input="inputCheck()" />
                            </div>

                            <div class="control">
                                <label for="title" class="label">Page Title:</label>
                                <span class="count">@{{title_width}} / 600 Pixels</span>
                                <input type="text" id="title" name="title" class="input" v-model="title" @input="inputCheck()" />
                            </div>

                            <div class="control">
                                <label for="title" class="label">URL</label>
                                <div id="shop_domain_el">@{{ shop_domain }}{{ $type }}/</div>
                                <div class="url-input">
                                    <span>@{{ shop_domain }}{{ $type }}/</span>
                                    <input v-bind:style="{ 'padding-left': shop_domain_width + 'px' }" type="text" id="url" name="url" v-model="url" @input="inputCheck()" />
                                </div>
                            </div>

                            <div v-if="has_custom_url()" class="control">
                                <label><input type="checkbox" v-model="checked">Create a URL redirect for @{{ url }}</label>
                            </div>

                            <div class="control">
                                <label for="description" class="label">Page Description:</label>
                                <span class="count">@{{description_length}} / 320 Characters</span>
                                <textarea class="textarea" id="description" name="description" v-model="description" @input="inputCheck()"></textarea>
                            </div>

                            <button type="button" @click="saveMeta()">Save</button>
                        </form>
                        <div class="alert success" v-if="show_status">
                            <dl>
                                <dt>Success Alert</dt>
                                <dd v-for="status in save_status">@{{ status }}</dd>
                            </dl>
                        </div>
                        <div id="seo-preview-hidden">
                            <span id="hidden_title">@{{ title }}</span> <span id="hidden_description">@{{ description }}</span>
                        </div>
                        <!-- Message -->
                        <div class="messages">
                            <p v-for="message in messages['problem']"><i class="fa fa-circle icon-problem" aria-hidden="true"></i> @{{ message }}</p>
                            <p v-for="message in messages['improvement']"><i class="fa fa-circle icon-improve" aria-hidden="true"></i> @{{ message }}</p>
                            <p v-for="message in messages['good']"><i class="fa fa-circle icon-good" aria-hidden="true"></i> @{{ message }}</p>
                        </div>
                    </div>
                </div>
            </article>
        </section>

        <section>
            <article>
                <div class="column six">
                    <div class="card">
                        <h3>How do I write a good title tag?</h3>
                        <p><b>Title tags should be short:</b> Shorter titles are easier for people to read and for search engines to crawl.</p>
                        <p><b>Title tags should contain your main focus keyword:</b> For best results, try to put your focus keyword as close to the beginning of your title as possible. That’s so search engines (and people) will see the keyword early on.</p>
                        <p><b>Title tags should describe a benefit:</b> Much like a headline, a title tag needs to communicate a benefit to stand out.</p>
                        <p><b>Give every page a unique title:</b> Unique titles help search engines understand that your content is unique and valuable, and also drive higher click-through rates.</p>
                        <p><b>Write for your customers:</b> While title tags are very important to SEO, remember that your first job is to attract clicks from well-targeted visitors who are likely to find your content valuable.</p>
                    </div>
                </div>

                <div class="column six">
                    <div class="card">
                        <h3>How do I write a good meta description tag?</h3>
                        <p><b>Keywords:</b> do make sure your most important keywords for the webpage show up in the meta description. Often search engines will highlight in bold where it finds the searchers query in your snippet.</p>
                        <p><b>Write legible, readable copy:</b> this is essential. Keyword stuffing your meta description is bad and it doesn’t help the searcher as they’ll assume your result leads to a spammy website. Make sure your description reads like a normal, human-written sentence.</p>
                        <p><b>Treat the meta description as if it’s an advert for your web-page:</b> make it as compelling and as relevant as possible. The description MUST match the content on the page, but you should also make it as appealing as possible.</p>
                        <p><b>Length:</b> a meta description should be no longer than 320 characters long (although Google has recently been testing longer snippets). Any longer and search engines will chop the end off, so make sure any important keywords are nearer the front.</p>
                        <p><b>Do not duplicate meta descriptions:</b> As with title tags, the meta descriptions must be written differently for every page. Google may penalise you for mass duplicating your meta descriptions.</p>
                        <p><b>Consider using rich snippets:</b> by using schema markup you can add elements to the snippets to increase their appeal. For instance: star ratings, customer ratings, product information, calorie counts etc.</p>

                    </div>
                </div>
            </article>
        </section>
    
        <script src="https://unpkg.com/vue"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="https://code.jquery.com/jquery.js"></script>
        
        @yield('script')
    </main>

</body>
</html>
