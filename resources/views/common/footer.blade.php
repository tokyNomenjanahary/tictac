 @push('scripts')
 <script type="text/javascript" src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625887/footer.min_patpux.js"></script>
 @endpush
<div id="footer">
 <div class="container-fluid p-0">
        <div class="foot">
            <div class="mobile-foot_ico">
                
            </div>
            <div class="row_top foot-row">

                <div class="cont_img"><img src="/img/blue-logo.png" alt=""></div>

                <div class="foot_line">
                    <span>{{__("footer.a_decouvrir")}}</span>
                    <div class="float-right foot_ico">
                        <a rel='nofollow' href="https://www.facebook.com/bailti/"> <img loading="lazy" src="{{ url('img/login/fb.png') }}" alt=""></a>
                    </div>
                </div>

            </div>
            <div class="row_bot foot-row">
                <div class="contact-us">
                    <div class="f-contact-div">
                        <span class="label-cnt">{{__('footer.nous_contacter')}}</span>
                        <span class="contactus">
                            <a href="mailto:contact@bailti.fr">{{__('footer.mail_footer')}}</a>
                        </span>
                    </div>
                    <div class="f-contact-div">
                        <span class="label-cnt">{{__('footer.phone_footer')}}</span>
                        <a class="contactus cnt-mobile" href="tel:{{__('footer.num_phone_link_footer')}}">
                            {{__('footer.num_phone_footer')}}
                        </a>
                    </div>
                     
                    <div class="f-contact-div">
                        <span><i class="fa fa-map-marker"></i></span>
                        <span class="contactus">
                            {{__('footer.adress_footer')}}
                        </span>
                    </div>
                    <div class="f-contact-div">
                        <span class="label-cnt">{{__('footer.maintenance')}}</span>
                        <span class="contactus">
                            <a href="mailto:{{__('footer.mail_maintenance')}}">{{__('footer.mail_maintenance')}}</a>
                        </span>
                    </div>
                </div>
                <div class="foot-right">
                    <div>
                        <div class="col-link">
                            <div class="foot-link-div">
                                <div class="foot-link">
                                    <a rel='nofollow' href="/condition-generale-utilisation">{{__('footer.cgu_footer')}}</a>
                                </div>
                                <div class="foot-link">
                                    <a rel='nofollow' href="/blog">{{__('footer.blog_footer')}}</a>
                                </div>
                            </div>
                            

                        </div>
                        <div class="col-link">
                            <div class="foot-link-div">
                                <div class="foot-link">
                                    <a rel='nofollow' href="/connexion">{{__('footer.se_connecter_footer')}}</a>
                                </div>
                                <div class="foot-link">
                                    <a rel='nofollow' href="/faq">{{__('footer.faq_footer')}}</a>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-link">
                            <div class="foot-link-div">
                                <div class="foot-link">
                                    <a rel='nofollow' href="/recrutement">{{__('footer.recrutement_footer')}}</a>
                                </div>
                                <div class="foot-link">
                                    <a rel='nofollow' href="/politique-de-confidentialite">{{__('footer.privacy_footer')}}</a>
                                </div> 
                            </div>
                            
                        </div>
                        
                    </div>
                </div>
                
            </div>
            <div class="blue_block">
                <div class="copyright text-right">
                    <span>&copy; Bailti<span class="font-weight-bold">
                     <script>document.write(new Date().getFullYear());</script>
                    </span> {{__('footer.tous_droit_footer')}}</span><br>

                </div>
            </div>
        </div>
    </div>
</div>
