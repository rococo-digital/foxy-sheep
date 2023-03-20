import '../scss/styles.scss';


//JS
const slick = require('slick-slider')

import "./app"
import "jquery-match-height/jquery.matchHeight";
import { burgerMenu } from './burgerMenu'
import { searchBar } from './searchBar'
import { loadMorePosts } from './loadMore'
import { gravityFormsLabels } from './gravityFormsLabels'
import { accordion } from './accordion'
import { productTabs } from './productTabs'
import {addToCartAjax, variationsDropdown} from './woocommerce'
import {sliders} from './sliders'
import {stickyHeader} from './stickyHeader'

//Inits
$(function(){
    burgerMenu.init();
    searchBar.init();
    loadMorePosts.init();
    gravityFormsLabels.init();
    accordion.init();
    productTabs.init();
    //woocommerce
    variationsDropdown.init();
    addToCartAjax.init();
    // miniCart.init();
    sliders.init();
    stickyHeader.init();
})