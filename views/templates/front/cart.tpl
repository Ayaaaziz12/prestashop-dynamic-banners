<div class="dynamic-banners cart-banners">
    {foreach from=$banners item=banner}
    <div class="banner-cart">
        <div class="alert alert-info">
            <a href="{$banner.link}" title="{$banner.title}">
                <strong>{$banner.title}</strong>
                <img src="{$image_path}{$banner.image}" alt="{$banner.title}" style="max-width: 100px; float: right;">
            </a>
        </div>
    </div>
    {/foreach}
</div>