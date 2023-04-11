$(document).ready(function () {
    $("div.card-trick").slice(0, 6).show();
    $("#load-more-button").on('click', function (e) {
        e.preventDefault();
        $("div.card-trick:hidden").slice(0, 6).slideDown();
        if ($("div.card-trick:hidden").length == 0) {
            $("#load-more-button").hide('slow');
            $("#load-less-button").show('slow');
        }
    });
    $("#load-less-button").on('click', function (e) {
        e.preventDefault();
        $("div.card-trick").slice(6, $("div.card-trick").length).hide();
        $("#load-less-button").hide('slow');
        $("#load-more-button").show('slow');

    });
})

const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
    const item = document.createElement('li');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    item.classList.add('list-group-item', 'list-unstyled');
    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;

    addTagFormDeleteLink(item);
};

const addTagFormDeleteLink = (item) => {
    const removeFormButton = document.createElement('button');
    removeFormButton.classList.add('btn', 'btn-primary');
    removeFormButton.innerText = 'Supprimer';

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        // remove the li for the tag form
        item.remove();
    });
}

document
    .querySelectorAll('.add_item_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection)
    });

document
    .querySelectorAll('ul.movie li')
    .forEach((tag) => {
        addTagFormDeleteLink(tag)
    })

// add more trick
let commentContainer = document.querySelector("#comment")
let btn = document.querySelector("#load-more")
btn.addEventListener('click', function (e) {
    e.preventDefault();
    fetch(this.getAttribute("href"), {
            method: "POST",
            body: JSON.stringify({page: this.getAttribute('data-page')})
        },
    ).then(function (response) {
        return response.text();
    }).then(html => {
        if (this.getAttribute('data-page') >= this.getAttribute('data-number-of-pages')) {
            this.style.setProperty('display', 'none', 'important')
        }
        this.setAttribute('data-page', parseInt(this.getAttribute('data-page'), 10) + 1)
        commentContainer.insertAdjacentHTML('beforeend', html);
    })
})
