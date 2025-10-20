{* Template header *}
{if $banners}
<div class="dynamic-banners header-banners">
    {foreach from=$banners item=banner}
    <div class="banner-header">
        <a href="{$banner.link}" title="{$banner.title}">
            {if $banner.image}
            {* Vérification que l'image existe *}
            <img src="{$module_path}views/img/{$banner.image}" alt="{$banner.title}" 
                 onerror="this.style.display='none'">
            {/if}
            <span>{$banner.title}</span>
        </a>
    </div>
    {/foreach}
</div>

<style>
.header-banners {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 10px 0;
    text-align: center;
    border-bottom: 2px solid #5a67d8;
}

.banner-header {
    display: inline-block;
    margin: 0 10px;
}

.banner-header a {
    color: white;
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 15px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.banner-header a:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.banner-header img {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.banner-header span {
    font-size: 13px;
}
</style>
{/if}