
@push('styles')
<style>
.section-gap {
    margin-top:0px;
    background-color: rgb(216, 216, 216);
}
.direction-ltr {
    direction:ltr
}
.direction-rtl {
    direction:rtl
}
.inline-col-wrapper {
    display:grid;
    grid-template-columns:auto 1fr;
    grid-gap:60px;
    align-items:center
}
.inline-col-wrapper .inline-col-image-wrapper {
    overflow:hidden
}
.inline-col-wrapper .inline-col-image-wrapper img {
    max-width:100%;
    height:auto;
    border-radius:16px;
    text-indent:-9999px
}
.inline-col-wrapper .inline-col-content-wrapper {
    display:flex;
    flex-wrap:wrap;
    gap:20px;
    max-width:464px
}
.inline-col-wrapper .inline-col-content-wrapper .inline-col-title {
    max-width:442px;
    font-size:60px;
    font-weight:400;
    color:#060c3b;
    line-height:70px;
    font-family:DM Serif Display;
    margin:0
}
.inline-col-wrapper .inline-col-content-wrapper .inline-col-description {
    margin:0;
    font-size:18px;
    color:#6e6e6e;
    font-family:Poppins
}
@media (max-width:991px) {
    .inline-col-wrapper {
        grid-template-columns:1fr;
        grid-gap:16px
    }
    .inline-col-wrapper .inline-col-content-wrapper {
        gap:10px
    }
}
@media (max-width:768px) {
    .inline-col-wrapper .inline-col-image-wrapper img {
        width:100%;
    }
    .inline-col-wrapper .inline-col-content-wrapper .inline-col-title {
        font-size:28px !important;
        line-height:normal !important
    }
}
@media (max-width:525px) {
    .inline-col-wrapper .inline-col-content-wrapper .inline-col-title {
        font-size:20px !important;
    }
    .inline-col-description {
        font-size:16px
    }
    .inline-col-wrapper {
        grid-gap:10px
    }
    .info_btn {
        margin-bottom: 16px;
    }
    .bold_sec {
        padding-right: 20px;
        padding-left: 20px;
    }
}
</style>
@endpush

<div class="section-gap bold-collections container bold_sec" >
    <div class="inline-col-wrapper" style="line-height: 40px;">
        <div class="inline-col-image-wrapper">
            <img src="" data-src="storage/theme/6/jAMlLJ8J5ja2D5mgW9z1XWjxsTfxEXcbO8dhNUa9.webp" class="lazy" width="450" height="450" alt="Get Ready for our new Gem Collections!" style="height: 450px;width: 450px;">
        </div>
        <div class="inline-col-content-wrapper" style="line-height: 40px; display:block;">
             <h5 class="inline-col-title" style="font-size:20px; color: rgb(164, 155, 155); line-height: 20px;"> Live Gemtone </h5>

            	<h3 class="inline-col-title" style="font-size:30px; color: rgb(102, 101, 101); line-height: 32px;"> Auctions </h3>

            	<h5 class="inline-col-title" style="font-size:20px; color: rgb(164, 155, 155); line-height: 20px;">Bid on Rare Beauty</h5>

            <p class="inline-col-description" style="font-size:13px; color:#808080; line-height: 22px; margin-top: 15px;">Discover a curated collection of precious gemstones available for live auction. Place your bids in real time and own exquisite, certified gemes at competitive prices. Each auction features detailed images, grading reports, and transparent biddind.Whether you're a collector or reseller, don't miss your chance to win rare finds. Join the excitement - new auction are added weekly.
                <br>Start bidding now and secure the gem of your dreams!</p>
            <button class="primary-button max-md:px-4 max-md:py-2.5 max-md:text-sm info_btn" style="border-radius: 0;margin-top: 20px;padding: 8px;background-color: rgb(255, 170, 19);border: none;font-weight: bold;">More Info</button>
        </div>
    </div>
</div>