<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Product Meta Tags</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('css/uptown.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://use.fontawesome.com/517309d031.js"></script>

    <style>
        .good {
            color: green;
        }

        .bad {
            color: red;
        }

        .improve {
            color: blue;
        }
    </style>
</head>
<body>
    {{-- <div class="loading" v-show="loading">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div> --}}
    <main>
        <header>
            <h1>Meta Tag Optimization</h1>
            <h2>Simple and Free App Help You Optimize Meta Tag</h2>
        </header>
        <section>
            <article>
                <div class="card">
                    <div id="app" class="container">
                        <!-- SEO Preview -->
                        <div id="seo-preview-hidden">
                            <span id="hidden_title">@{{ form.title }}</span> <span id="hidden_description">@{{ form.description }}</span>
                        </div>
                        <div class="preview">
                            <div class="message-header">
                                <h3>SERP Preview</h3>
                            </div>
                            <div class="message-body">
                                <div id="seo-preview">
                                    <div class="seo-title-preview">
                                        <span id="out_title">@{{ form.title }}</span>
                                    </div>
                                    <div class="seo-url-preview">
                                        <!--<span id="out_date"></span>-->
                                        <span id="out_url">www.smartsearchmarketing.com/seo-widget.html</span> <!--<span id="out_dash1"></span>-->
                                    </div>
                                    <div class="seo-description-preview">
                                        <!--<span id="out_date"></span><span id="out_datedots">&nbsp;-&nbsp;</span>-->
                                        <span id="out_snippet">@{{ form.snippet }}</span>
                                    </div>
                                </div>
                            </div>
                        </div><!--//.seo-preview-->

                        <form>
                            <input type="hidden" name="product_id" value="{{ $product_id }}">
                            <div class="control">
                                <label for="keyword" class="label">Page Keyword:</label>
                                <input type="text" id="keyword" name="keyword" class="input" v-model="form.keyword" @input="form.inputCheck()" />
                            </div>

                            <div class="control">
                                <label for="title" class="label">Page Title:</label>
                                <input type="text" id="title" name="title" class="input" v-model="form.title" @input="form.inputCheck()" />
                            </div>

                            <div class="control">
                                <label for="description" class="label">Page Description:</label>
                                
                                <textarea class="textarea" id="description" name="description" v-model="form.description" @input="form.inputCheck()"></textarea>
                            </div>
                        </form>

                        <!-- Message -->
                        <div class="messages">
                            <p v-for="message in form.messages['problem']"><i class="fa fa-circle icon-problem" aria-hidden="true"></i> @{{ message }}</p>
                            <p v-for="message in form.messages['improvement']"><i class="fa fa-circle icon-improve" aria-hidden="true"></i> @{{ message }}</p>
                            <p v-for="message in form.messages['good']"><i class="fa fa-circle icon-good" aria-hidden="true"></i> @{{ message }}</p>
                        </div>
                    </div>
                </div>
            </article>
        </section>

        <script src="https://unpkg.com/vue"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="https://code.jquery.com/jquery.js"></script>
        <script>
            Vue.config.debug = true; 
            Vue.config.devtools = true;

            class Preview {
                constructor(data) {
                    this.originalData = data;

                    for(let field in data) {
                        this[field] = data[field];
                    }
                }

                data() {
                    let data = {};

                    for (let property in this.originalData) {
                        data[property] = this[property];
                    }

                    return data;
                }
            }

            class Form {
                constructor(data) {
                    this.originalData = data;

                    for (let field in data) {
                        this[field] = data[field];
                    }
                }

                data() {
                    let data = {};

                    for (let property in this.originalData) {
                        data[property] = this[property];
                    }

                    return data;
                }

                // Truncate Description
                truncate(str, maxLength, suffix){
                    if(str.length > maxLength)
                    {
                        str = str.substring(0, maxLength + 1); 
                        str = str.substring(0, Math.min(str.length, str.lastIndexOf(" ")));
                        str = str + suffix;
                    }
                    return str;
                }

                // Generate Meta Title or Meta Description
                value(meta) {
                    axios.get('/api/product', {
                        params: {
                            ids: $('input[type=hidden]').val()
                        }
                    }).then((response) => {
                        if(meta == "title") {
                            var responseData = response.data.body.products[0];
                            this.title = responseData.title;
                        } else if(meta == "description") {
                            var descriptionData = "";
                            descriptionData = responseData.body_html.replace(/(<([^>]+)>)/ig,"");
                            descriptionData = descriptionData.replace(/\r?\n|\r/g, "");
                            this.description = descriptionData;
                        }
                    });
                }

                keywordCheck() {
                    if(this.keyword && this.keyword.length ) {
                        if(this.messages["problem"].hasOwnProperty("has_keyword")) {
                            delete this.messages["problem"]["has_keyword"];
                        }
                        return true;
                    } else {
                        if(this.messages["good"].hasOwnProperty("has_keyword")) {
                            delete this.messages["good"]["has_keyword"];
                        } else if(this.messages["improvement"].hasOwnProperty("has_keyword")) {
                            delete this.messages["improvement"]["has_keyword"];
                        }
                        this.messages["problem"]["has_keyword"] = "You do not add any keyword";
                        return false;
                    }
                }

                titleCheck(title) {
                    console.log(this.title);
                    var title_width = document.getElementById("hidden_title").offsetWidth;
                    if(title_width > 600) {
                        if(this.messages["good"].hasOwnProperty("tl")) {
                            delete this.messages["good"]["tl"];
                        } else if(this.messages["improvement"].hasOwnProperty("tl")) {
                            delete this.messages["improvement"]["tl"];
                        }
                        this.messages["problem"]["tl"] = "Your title is too long";
                    } else {
                        if(this.messages["problem"].hasOwnProperty("tl")) {
                            delete this.messages["problem"]["tl"];
                        } else if(this.messages["improvement"].hasOwnProperty("tl")) {
                            delete this.messages["improvement"]["tl"];
                        }
                        this.messages["good"]["tl"] = "Your title is good ";
                    }
                }

                descriptionCheck() {
                    // Check length of description
                    console.log(this.description);
                    if(this.description.length >= 320) {
                        console.log(this.description.length);
                        if(this.messages["good"].hasOwnProperty("ml")) {
                            delete this.messages["good"]["ml"];
                        } else if(this.messages["improvement"].hasOwnProperty("ml")) {
                            delete this.messages["improvement"]["ml"];
                        }
                        this.messages["problem"]["ml"] = "Your description is too long";
                    } else {
                        if(this.messages["problem"].hasOwnProperty("ml")) {
                            delete this.messages["problem"]["ml"];
                        } else if(this.messages["improvement"].hasOwnProperty("ml")) {
                            delete this.messages["improvement"]["ml"];
                        }
                        this.messages["good"]["ml"] = "Your description is good";
                    }
                }

                inputCheck() {
                    // Run default check function
                    this.titleCheck();
                    this.keywordCheck();
                    this.descriptionCheck();
                    this.snippet = this.truncate(this.description.replace(/^\s+/g, ''), 320, "...");

                    // Check if keyword apprear in title tag and if yes, check keyword position in title
                    if(this.keywordCheck()) {
                        if(this.title.toLowerCase().search(this.keyword.toLowerCase()) >= 0) {
                            if(this.messages["problem"].hasOwnProperty("tk")) {
                                delete this.messages["problem"]["tk"];
                            }
                            var keyword_position = this.title.indexOf(this.keyword);
                            if(keyword_position == 0) {
                                if(this.messages["improvement"].hasOwnProperty("tkp")) {
                                    delete this.messages["improvement"]["tkp"];
                                }
                                this.messages["good"]["tkp"] = "Good Keyword Position";
                            } else {
                                if(this.messages["good"].hasOwnProperty("tkp")) {
                                    delete this.messages["good"]["tkp"];
                                }
                                this.messages["improvement"]["tkp"] = "Not Good Keyword Position";
                            }
                        } else {
                            if(this.messages["good"].hasOwnProperty("tkp")) {
                                delete this.messages["good"]["tkp"];
                            } else if(this.messages["improvement"].hasOwnProperty("tkp")) {
                                delete this.messages["improvement"]["tkp"];
                            }
                            this.messages["problem"]["tk"] = "Keyword doens't apprear in title tag";
                        }
                    }

                    // Check if keyword apprear in description
                    if(this.keyword && this.keyword.length ) {
                        if(this.snippet.toLowerCase().search(this.keyword.toLowerCase()) < 0) {
                            if(this.messages["good"].hasOwnProperty("mk")) {
                                delete this.messages["good"]["mk"];
                            }
                            this.messages["improvement"]["ml"] = "You should add keyword into meta description";
                        } else {
                            if(this.messages["improvement"].hasOwnProperty("mk")) {
                                delete this.messages["improvement"]["mk"];
                            }
                            this.messages["good"]["ml"] = "Your keyword appreared in meta description";

                            // Bold keyword in meta description
                        }
                    }
                }
            }

            new Vue({
                el: '#app',
                data: {
                    form: new Form({
                        shop_name: '',
                        keyword: '',
                        title: '',
                        description: '',
                        snippet: '',
                        messages: {
                            good: {},
                            improvement: {},
                            problem: {}
                        },
                        loading: false,
                    })
                },
                mounted: function () {
                    this.$nextTick(function () {
                        // Get Shop information
                        axios.get('/api/shop')
                            .then((response) => {
                                this.form.shop_name = response.data.body.shop.name;
                            });

                        // Set Default Meta Title and Meta Description
                        axios.get('/api/meta-tags', {
                            params: {
                                product_id: $('input[type=hidden]').val()
                            }
                        }).then((response) => {
                            var responseData = response.data.body.metafields;
                            if(responseData && responseData.length) {
                                for(let singleData of responseData) {
                                    if(singleData.key == "title_tag") {
                                        if(singleData.value.length) {
                                            this.form.title = singleData.value;
                                        } else {
                                            var meta = "title";
                                            this.form.value(meta);
                                        }
                                        this.form.title += "\xa0-\xa0" + this.form.shop_name;
                                    } else if(singleData.key == "description_tag") {
                                        if(singleData.value.length) {
                                            this.form.description = singleData.value;
                                        } else {
                                            var meta = "description";
                                            this.form.value(meta);
                                        }
                                    }
                                }
                            } else {
                                axios.get('/api/product', {
                                    params: {
                                        ids: $('input[type=hidden]').val()
                                    }
                                }).then((response) => {
                                    var responseData = response.data.body.products[0];
                                    this.form.title = responseData.title;
                                    this.form.title += "\xa0-\xa0" + this.form.shop_name;
                                    this.form.description = responseData.body_html.replace(/<\/?[\w\s="/.':;#-\/\?]+>/gi,"");
                                    this.form.description = this.form.description.replace(/\r?\n|\r/g, "");
                                    this.form.snippet = this.form.truncate(this.form.description.replace(/^\s+/g, ''), 320, "...");

                                });
                            }
                        });
                        this.form.inputCheck();
                    });
                }
            });
            
        </script>
    </main>

</body>
</html>
