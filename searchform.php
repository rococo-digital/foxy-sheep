<?php
$search_placeholder = 'Search';
$form = '<form role="search"  aria-label="Search form" method="get" id="searchform" class="searchform" action="' . esc_url( home_url( '/' ) ) . '">
<div>
    <label class="screen-reader-text" for="s">' . _x( $search_placeholder, 'label' ) . '</label>
    <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="'.  $search_placeholder .'" />
    <button class="no-style-button" data-trigger-search>
          <svg xmlns="http://www.w3.org/2000/svg" width="25.622" height="25.621" viewBox="0 0 25.622 25.621">
          <g id="Group_52" data-name="Group 52" transform="translate(-1124.398 -104.56)">
            <g id="Group_51" data-name="Group 51">
              <circle id="Ellipse_2" data-name="Ellipse 2" cx="8.899" cy="8.899" r="8.899" transform="translate(1125.898 106.06)" fill="none" stroke="#FFFFFF" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3"/>
              <line id="Line_7" data-name="Line 7" x2="6.795" y2="6.795" transform="translate(1141.104 121.266)" fill="none" stroke="#FFFFFF" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3"/>
            </g>
          </g>
        </svg>
    </button>
    <input type="submit" id="searchsubmit" value="' . esc_attr_x( 'Search', 'submit button' ) . '" />
</div>
</form>';

echo $form;