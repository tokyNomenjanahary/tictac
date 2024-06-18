<div class="seo-snippet-area">
        <div class="seo-snippet-area-content">
            <h3 class="seo_snippet_title">
            @if(!is_null($infosAddress))
            <?php echo __('acceuil.seo_snippet_title', ['ville' => $infosAddress['ville']]);?>
            @else
            <?php echo __('acceuil.seo_snippet_title', ['ville' => "France"]);?>
            @endif
            {{__('acceuil.seo_snippet_title')}}
            </h3>

            <div id="seo_snippet_text">
                <p>
                    @if(!is_null($infosAddress) && $infosAddress['ville'] == "Paris")
                    <?php echo __('acceuil.seo_snippet_text1Paris');?>
                    @else
                    <?php echo __('acceuil.seo_snippet_text1');?>
                    @endif
                </p>
            </div>

            <div class="seo-snippet-images">
                <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="https://static.erm-assets.com/r1-116-0-450/assets/img/location-seo-content/FR/top-bordeaux.jpg" class="seo-snippet-image">

                <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="https://static.erm-assets.com/r1-116-0-450/assets/img/location-seo-content/FR/top_montpellier.jpg" class="seo-snippet-image seo-snippet-image--hide-small-screen">
                
                <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="https://static.erm-assets.com/r1-116-0-450/assets/img/location-seo-content/FR/top_paris.jpg" class="seo-snippet-image seo-snippet-image--hide-small-screen">
            </div>

            <div id="seo_snippet_text">
                <p>
                    @if(!is_null($infosAddress) && $infosAddress['ville'] == "Paris")
                    <?php echo __('acceuil.seo_snippet_text2Paris');?>
                    @else
                    <?php echo __('acceuil.seo_snippet_text2');?>
                    @endif
                </p>
            </div>

        </div>
    </div>