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

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;

    addTagFormDeleteLink(item);
};

const addTagFormDeleteLink = (item) => {
    const removeFormButton = document.createElement('button');
    removeFormButton.innerText = 'Delete this tag';

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
