{* Template par défaut pour toutes les positions *}
{if $banners}
<div class="dynamic-banners default-banners position-{$position}">
    {foreach from=$banners item=banner}
    <div class="banner-default">
        <a href="{$banner.link}" title="{$banner.title}">
            {if $banner.image && file_exists($physical_image_path|cat:$banner.image)}
            <img src="{$image_path}{$banner.image}" alt="{$banner.title}">
            {elseif $banner.image}
            <span class="no-image">🖼️ Image manquante: {$banner.image}</span>
            {/if}
            <span class="banner-title">{$banner.title}</span>
        </a>
    </div>
    {/foreach}
</div>

<style>
.default-banners {
    padding: 10px;
    margin: 10px 0;
    border: 2px dashed #ccc;
    background: #f9f9f9;
}

.banner-default {
    margin: 10px 0;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background: white;
}

.banner-default a {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: #333;
}

.banner-default img {
    max-width: 50px;
    height: auto;
    border-radius: 3px;
}

.no-image {
    color: #ff4444;
    font-size: 12px;
}

.banner-title {
    font-weight: bold;
}
</style>
{/if}