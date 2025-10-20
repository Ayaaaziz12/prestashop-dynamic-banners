<div class="dynamic-banners footer-banners">
    {foreach from=$banners item=banner}
    <div class="banner-footer">
        <a href="{$banner.link}" title="{$banner.title}">
            <img src="{$image_path}{$banner.image}" alt="{$banner.title}">
            <span>{$banner.title}</span>
        </a>
    </div>
    {/foreach}
</div>

<style>
.footer-banners {
    background: #f8f9fa;
    padding: 15px 0;
    border-top: 1px solid #dee2e6;
}

.banner-footer {
    text-align: center;
    margin: 10px 0;
}

.banner-footer img {
    max-width: 100px;
    height: auto;
    display: block;
    margin: 0 auto 5px;
}

.banner-footer span {
    display: block;
    font-size: 12px;
    color: #6c757d;
}
</style>