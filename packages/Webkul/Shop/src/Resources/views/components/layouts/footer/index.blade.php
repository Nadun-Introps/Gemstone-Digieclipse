{!! view_render_event('bagisto.shop.layout.footer.before') !!}

@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

@php
    $channel = core()->getCurrentChannel();

    $customization = $themeCustomizationRepository->findOneWhere([
        'type' => 'footer_links',
        'status' => 1,
        'theme_code' => $channel->theme,
        'channel_id' => $channel->id,
    ]);
@endphp

<footer class="footer style7">
    <div class="container">
        <div class="container-wapper">
            <div class="row">
                {{-- Dynamic Footer Links --}}
                @if ($customization?->options)
                    @foreach ($customization->options as $footerLinkSection)
                        <div class="box-footer col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <div class="stelina-custommenu default">
                                <h2 class="widgettitle"> {{ __('shop::app.components.layouts.footer.footer-content') }}
                                </h2>
                                <ul class="menu">
                                    @php
                                        usort($footerLinkSection, function ($a, $b) {
                                            return $a['sort_order'] - $b['sort_order'];
                                        });
                                    @endphp

                                    @foreach ($footerLinkSection as $link)
                                        <li class="menu-item">
                                            <a href="{{ $link['url'] }}">{{ $link['title'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- Newsletter Section --}}
                @if (core()->getConfigData('customer.settings.newsletter.subscription'))
                    <div class="box-footer col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <div class="stelina-newsletter style1">
                            <div class="newsletter-head">
                                <h3 class="title">@lang('shop::app.components.layouts.footer.newsletter-text')</h3>
                            </div>
                            <div class="newsletter-form-wrap">
                                <div class="list">
                                    @lang('shop::app.components.layouts.footer.subscribe-stay-touch')
                                </div>

                                <x-shop::form :action="route('shop.subscription.store')" class="newsletter-form">
                                    <input type="email" class="input-text email email-newsletter" name="email"
                                        rules="required|email" placeholder="@lang('shop::app.components.layouts.footer.email')" />
                                    <x-shop::form.control-group.error control-name="email" />

                                    <button class="button btn-submit submit-newsletter">
                                        @lang('shop::app.components.layouts.footer.subscribe')
                                    </button>
                                </x-shop::form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Footer Bottom --}}
            <div class="footer-end">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="stelina-socials">
                            <ul class="socials">
                                <li><a href="#" class="social-item" target="_blank"><i
                                            class="icon fa fa-facebook"></i></a></li>
                                <li><a href="#" class="social-item" target="_blank"><i
                                            class="icon fa fa-twitter"></i></a></li>
                                <li><a href="#" class="social-item" target="_blank"><i
                                            class="icon fa fa-instagram"></i></a></li>
                            </ul>
                        </div>
                        <div class="coppyright">
                            @lang('shop::app.components.layouts.footer.footer-text', ['current_year' => date('Y')])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}
