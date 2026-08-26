(() => {

    jQuery(document).ready(function ($) {

        let category = $('#category').data('news-category')
        console.log(category)

        call_api('top-headlines', 1, 'en', '', category, '')
        sessionStorage.setItem('page', 1)


        $('.load-more button').on('click', () => {

            let page = Number(sessionStorage.getItem('page'))

            next = page + 1
            sessionStorage.setItem('page', next)

            console.log(next)

            call_api('top-headlines', next, 'en', '', category, '')

        })


        // append:   Parent → append Child [parent.append(child), Parent gets child]
        // appendTo: Child → appendTo Parent [child.appendTo(parent), Child goes to parent]

        function append_cards(data) {

            let articles = data.articles

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

                div_bottom.append(
                    $('<h2>').text(article.title),
                    $('<p>').text(article.description),
                    $('<span>').text(article.publishedAt)
                )

                div_bottom.appendTo(link)

                link.appendTo(card)

                $('.container').append(card)
            })

        }

        function call_api(endpoint, page, lang, query, category, country) {
            $.ajax({
                url: ajax.endpoint + "?action=get_news",
                type: 'POST',
                data: JSON.stringify({ endpoint: endpoint, page: page, lang: lang, query: query, category: category, country: country }),
                success: function (res, status) {
                    console.log(res.success)
                    console.log(JSON.parse(res.data))
                    append_cards(JSON.parse(res.data))
                },
                error: function (error, status) {
                    console.log(error)
                }
            })
        }

    })
})()