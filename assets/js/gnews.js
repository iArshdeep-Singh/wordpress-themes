(() => {

    jQuery(document).ready(function ($) {

        let category = $('.container').data('news-category')
        let endpoint = $('.container').data('news-endpoint')
        let language = $('.container').data('news-lang')

        const params = new URLSearchParams(window.location.search);

        const query = params.get("s");

        console.log(category)
        console.log(endpoint)
        console.log(query)
        console.log(language)


        call_api(endpoint, 1, language, query, category, 'in')
        sessionStorage.setItem('page', 1)

        console.log(window.location.href)

        $('.load-more button').on('click', () => {

            let page = Number(sessionStorage.getItem('page'))

            next = page + 1
            sessionStorage.setItem('page', next)

            console.log(next)

            call_api(endpoint, next, language, query, category, 'in')

        })


        // append:   Parent → append Child [parent.append(child), Parent gets child]
        // appendTo: Child → appendTo Parent [child.appendTo(parent), Child goes to parent]

        function append_cards(data) {

            let articles = data?.articles

            if (!articles) {
                return
            }

            if (articles && articles.length == 0) {
                $('.container').append("<p style='color: #0b1f3f; font-style: italic; font-size: 1.5vw;'>Nothing found</p>")
                $('.load-more button').css({ "display": "none" })
            }


            articles.forEach(article => {
                let card = $('<div>', {
                    class: 'news-card'
                })

                let link = $('<a>', {
                    href: article.url,
                })


                let image = $('<img>', {
                    src: article.image,
                    alt: article.title
                })


                let div_top = $('<div>', {
                    class: "top-bottom"
                })


                div_top.append(image)
                div_top.appendTo(link)


                let div_bottom = $('<div>', {
                    class: 'bottom-content'
                })

                div_top.append(image)
                div_top.appendTo(link)

                let description = ""
                let title = ""

                if (article.description.length > 150) {
                    description = article.description.substring(0, 150) + "..."
                } else {
                    description = article.description
                }

                if (article.title.length > 60) {
                    title = article.title.substring(0, 60) + "..."
                } else {
                    title = article.title
                }

                div_bottom.append(
                    $('<h2>').text(title),
                    $('<p>').text(description),
                    $('<span>').text(article?.source?.name)
                )

                div_bottom.appendTo(link)

                link.appendTo(card)

                $('.container').append(card)
            })

            if ((articles.length < 8 && endpoint == "top-headlines" && articles.length != 0) || (articles.length < 10 && endpoint == "search" && articles.length != 0)) {
                $(".load-more button").css({ "display": "none" })
            }

        }

        function call_api(endpoint, page, lang, query, category, country) {

            $('.load-more button').css({ "color": "#0072b8", "border": "none", "font-size": "medium", "cursor": "unset" }).text("Loading...").prop("disabled", true)

            $.ajax({
                url: ajax.endpoint + "?action=get_news",
                type: 'POST',
                data: JSON.stringify({ endpoint: endpoint, page: page, lang: lang, query: encodeURIComponent(query).trim(), category: category, country: country }),
                success: function (res, status) {

                    let parsedData = JSON.parse(res.data)

                    console.log(res.success)
                    console.log(status)
                    console.log(parsedData)

                    if (parsedData.errors) {
                        $('.container').append(`<p style='color: red; font-style: italic; font-size: 1.5vw;'>${parsedData.errors[0]}</p>`)
                        $('.load-more button').css({ "display": "none" })
                    }

                    append_cards(parsedData)
                    $('.load-more button').text("Load More").prop("disabled", false).css({ "cursor": "pointer" })

                },
                error: function (xhr, status, error) {
                    $('.container').append(`<p style='color: red; font-style: italic; font-size: 1.5vw;'>Status Code: ${xhr.status}<br>${error}</p>`)
                    $('.load-more button').css({ "display": "none" })
                }
            })
        }

    })

})()