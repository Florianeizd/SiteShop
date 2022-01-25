/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

import '@popperjs/core';
import 'bootstrap';

// start the Stimulus application
import './bootstrap';


const btnAddImageArticle = document.querySelector('button.add-item-image-article');
btnAddImageArticle.addEventListener('click', function (e) {
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
})

