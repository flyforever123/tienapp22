new Vue({
    el: '#app',
    data: {
        shop_name: '',
        shop_domain: '',
        shop_domain_width: null,
        keyword: '',
        title: '',
        has_custom_title: null,
        custom_title_id: null,
        title_width: null,
        default_url: '',
        url: '',
        checked: true,
        description: '',
        has_custom_desc: null,
        custom_desc_id: null,
        description_length: null,
        snippet: '',
        message: '',
        messages: {
            good: {},
            improvement: {},
            problem: {}
        },
        preloading: true,
        show_status: false,
        save_status: [],
    },
    mounted: function () {
        this.default_shop_name().then(this.getWidth).then(this.default_data).then(this.inputCheck).then(() => this.preloading = false);
    },
    methods: {
        // Truncate Description
        truncate(str, maxLength, suffix){
            if(str.length > maxLength)
            {
                str = str.substring(0, maxLength + 1); 
                str = str.substring(0, Math.min(str.length, str.lastIndexOf(" ")));
                str = str + suffix;
            }
            return str;
        },

        // Get Shop name
        default_shop_name: function() {
            return axios.get('/api/shop')
                    .then((response) => {
                            this.shop_name = response.data.body.shop.name;
                            this.shop_domain = "https://" + response.data.body.shop.domain + "/";
                    });
        },

        // Get Width URL
        getWidth() {
            this.$nextTick(function () {
                this.shop_domain_width = document.getElementById("shop_domain_el").offsetWidth;
            });
        },

        saveMeta() {

            // POST Meta Title
            axios.post('/api/save/title', {
                id: $('input[type=hidden]').val(),
                title_value: this.title.replace(this.shop_name, '').replace('\xa0-\xa0', ''),
            }).then((response) => {
                this.show_status = true;
                this.save_status.push('Title tag have been saved');
            });

            // POST Meta Description
            axios.post('/api/save/desc', {
                id: $('input[type=hidden]').val(),
                desc_value: this.description
            }).then((response) => {
                this.show_status = true;
                this.save_status.push('Description tag have been saved');
            });

            // POST Url
            axios.put('/api/save-url', {
                id: $('input[type=hidden]').val(),
                url_value: this.url.replace(/ /g, '-').replace(/[^\w-]+/g,'')
            }).then((response) => {
                this.url = this.url.replace(/ /g, '-').replace(/[^\w-]+/g,'');
                this.show_status = true;
                this.save_status.push('Custom URL have been saved');
            });

            if(this.checked == true) {
                axios.post('/api/redirect', {
                    path: '\/products\/' + this.default_url.replace(/ /g, '-').replace(/[^\w-]+/g,''),
                    target: '\/products\/' + this.url.replace(/ /g, '-').replace(/[^\w-]+/g,'')
                }).then((response) => {
                    this.default_url = this.url.replace(/ /g, '-').replace(/[^\w-]+/g,'');
                    this.show_status = true;
                    this.save_status.push('Redirect have been created');
                });
            }

            return false;

        },

        // Default Data
        default_data: function() {
            return axios.get('/api/meta-tags', {
                params: {
                    product_id: $('input[type=hidden]').val()
                }
            }).then((response) => {
                var responseData = response.data.body.metafields;
                if(responseData && responseData.length) {
                    for(let singleData of responseData) {
                        if(singleData.key == "title_tag") {
                            if(singleData.value.length) {
                                this.title = singleData.value;
                                this.title += "\xa0-\xa0" + this.shop_name;
                                this.has_custom_title = true;
                            }
                        } else if(singleData.key == "description_tag") {
                            if(singleData.value.length) {
                                this.description = singleData.value;
                                this.has_custom_desc = true;
                            }
                        }
                    }
                }

                if(this.title == '' || this.description == '' || this.url == '') {
                    return axios.get('/api/product', {
                        params: {
                            ids: $('input[type=hidden]').val()
                        }
                    }).then((response) => {
                        var responseData = response.data.body.products[0];
                        if(this.url == '') {
                            this.url = responseData.handle;
                            this.default_url = responseData.handle;
                        }
                        if(this.title == '') {
                            this.title = responseData.title;
                            this.title += "\xa0-\xa0" + this.shop_name;
                            this.has_custom_title = false;
                        }
                        if(this.description == '') {
                            var descriptionData = "";
                            descriptionData = responseData.body_html.replace(/(<([^>]+)>)/ig,"");
                            descriptionData = descriptionData.replace(/\r?\n|\r/g, "");
                            this.description = descriptionData;
                            this.description = this.truncate(this.description.replace(/^\s+/g, ''), 320, "...");
                            this.has_custom_desc = false;
                        }
                    });
                }
            });
        },

        has_custom_url() {
            if(this.default_url != this.url) {
                return true;
            } else {
                return false;
            }
        },

        titleCheck() {
            this.$nextTick(function () {
                var title_width = document.getElementById("hidden_title").offsetWidth;
                this.title_width = title_width;
                if(this.title_width > 600) {
                    if(this.messages["good"].hasOwnProperty("tl")) {
                        delete this.messages["good"]["tl"];
                    } else if(this.messages["improvement"].hasOwnProperty("tl")) {
                        delete this.messages["improvement"]["tl"];
                    }
                    this.messages["problem"]["tl"] = "The SEO title is wider than the viewable limit.";
                } else if(this.title.length.length < 50) {
                    if(this.messages["good"].hasOwnProperty("tl")) {
                        delete this.messages["good"]["tl"];
                    } else if(this.messages["problem"].hasOwnProperty("tl")) {
                        delete this.messages["problem"]["tl"];
                    }
                    this.messages["improvement"]["tl"] = "The SEO title is too short. Use the space to add keyword variations or create compelling call-to-action copy.";
                } else {
                    if(this.messages["problem"].hasOwnProperty("tl")) {
                        delete this.messages["problem"]["tl"];
                    } else if(this.messages["improvement"].hasOwnProperty("tl")) {
                        delete this.messages["improvement"]["tl"];
                    }
                    this.messages["good"]["tl"] = "The SEO title has a nice length";
                }
            });
        },

        keywordCheck() {
            this.$nextTick(function () {
                if(this.keyword && this.keyword.length) {
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
                    this.messages["problem"]["has_keyword"] = "No focus keyword was set for this page. If you do not set a focus keyword, no score can be calculated.";
                    return false;
                }
            });

            // Check if keyword apprear in title tag and if yes, check keyword position in title
                if(this.keyword && this.keyword.length) {
                    if(this.title.toLowerCase().search(this.keyword.toLowerCase()) >= 0) {
                        var keyword_position = this.title.toLowerCase().indexOf(this.keyword.toLowerCase());
                        if(this.messages["problem"].hasOwnProperty("tkp")) {
                            delete this.messages["problem"]["tkp"];
                        }
                        if(keyword_position == 0) {
                            if(this.messages["improvement"].hasOwnProperty("tkp")) {
                                delete this.messages["improvement"]["tkp"];
                            }
                            this.messages["good"]["tkp"] = "The SEO title contains the focus keyword, at the beginning which is considered to improve rankings.";
                        } else {
                            if(this.messages["good"].hasOwnProperty("tkp")) {
                                delete this.messages["good"]["tkp"];
                            }
                            this.messages["improvement"]["tkp"] = "The SEO title contains the focus keyword, but it does not appear at the beginning; try and move it to the beginning.";
                        }
                    } else {
                        if(this.messages["good"].hasOwnProperty("tkp")) {
                            delete this.messages["good"]["tkp"];
                        } else if(this.messages["improvement"].hasOwnProperty("tkp")) {
                            delete this.messages["improvement"]["tkp"];
                        }
                        this.messages["problem"]["tkp"] = "The focus keyword '"+ this.keyword +"' does not appear in the SEO title.";
                    }
                }

                // Check if keyword apprear in description
                if(this.keyword && this.keyword.length ) {
                    if(this.snippet.toLowerCase().search(this.keyword.toLowerCase()) < 0) {
                        if(this.messages["good"].hasOwnProperty("mk")) {
                            delete this.messages["good"]["mk"];
                        }
                        this.messages["improvement"]["mk"] = "You should add keyword into meta description";
                    } else {
                        if(this.messages["improvement"].hasOwnProperty("mk")) {
                            delete this.messages["improvement"]["mk"];
                        }
                        this.messages["good"]["mk"] = "Your keyword appreared in meta description";
                    }
                }
        },

        descriptionCheck() {
            this.$nextTick(function () {
                // Check length of description
                this.description_length = this.description.length;
                if(this.description.length > 320) {
                    if(this.messages["good"].hasOwnProperty("ml")) {
                        delete this.messages["good"]["ml"];
                    } else if(this.messages["improvement"].hasOwnProperty("ml")) {
                        delete this.messages["improvement"]["ml"];
                    }
                    this.messages["problem"]["ml"] = "The meta description is over 320 characters. Reducing the length will ensure the entire description will be visible.";
                } else if(this.description_length < 120) {
                    if(this.messages["good"].hasOwnProperty("ml")) {
                        delete this.messages["good"]["ml"];
                    } else if(this.messages["problem"].hasOwnProperty("ml")) {
                        delete this.messages["problem"]["ml"];
                    }
                    this.messages["improvement"]["ml"] = "The meta description is under 120 characters long. However, up to 320 characters are available.";
                } else {
                    if(this.messages["problem"].hasOwnProperty("ml")) {
                        delete this.messages["problem"]["ml"];
                    } else if(this.messages["improvement"].hasOwnProperty("ml")) {
                        delete this.messages["improvement"]["ml"];
                    }
                    this.messages["good"]["ml"] = "The meta description has a nice length.";
                }
            });
        },

        inputCheck() {
            this.$nextTick(function () {
                // Run default check function
                this.keywordCheck();
                this.titleCheck();
                this.descriptionCheck();
                this.snippet = this.truncate(this.description.replace(/^\s+/g, ''), 320, "...");
            });
        }
    },
});