<div class="dynamic-banners home-banners">
    {foreach from=$banners item=banner}
    <div class="banner-item banner-home">
        <a href="{$banner.link}" title="{$banner.title}">
            <img src="{$image_path}{$banner.image}" alt="{$banner.title}" class="img-responsive">
            <div class="banner-content">
                <h3>{$banner.title}</h3>
            </div>
        </a>
    </div>
    {/foreach}
</div>

<style>
.dynamic-banners {
    margin: 20px 0;
    text-align: center;
}

.banner-item {
    position: relative;
    margin-bottom: 20px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.banner-item img {
    width: 100%;
    height: auto;
    display: block;
}

.banner-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 10px;
}

.banner-content h3 {
    margin: 0;
    font-size: 18px;
    font-weight: bold;
}

/* Responsive */
@media (max-width: 768px) {
    .banner-item {
        margin-bottom: 10px;
    }
    
    .banner-content h3 {
        font-size: 14px;
    }
}
</style>