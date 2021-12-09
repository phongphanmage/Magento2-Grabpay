/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/* @api */

define([
    'jquery'
], function (jQuery
) {
    if(typeof GrabWidget === 'undefined'){
        var GrabWidget = {};
        GrabWidget.availableInstallment = 4;
        GrabWidget.money_format = "${{amount}}";
        GrabWidget.grabFrameLogo = `<svg width="83" height="77" viewBox="0 0 83 77" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0)"><path d="M33.9677 76.3003C36.6562 73.7825 40.3261 72.2463 44.2947 72.2463 48.2634 72.2463 51.9333 73.7825 54.6217 76.3003 56.7554 70.4967 62.303 66.4 68.832 66.4 69.8988 66.4 70.9657 66.528 71.9898 66.7414 71.8618 65.9306 71.7765 65.0771 71.7765 64.181 71.7765 57.1825 76.5132 51.2936 82.9569 49.544 79.9698 46.7702 78.1348 42.8442 78.1348 38.4488 78.1348 34.0535 80.0125 30.1275 82.9569 27.3537 77.196 25.22 73.0567 19.6725 73.0567 13.1434 73.0567 12.1193 73.142 11.0951 73.3554 10.1136 72.2886 10.3697 71.179 10.4977 70.0269 10.4977 63.2844 10.4977 57.5662 6.10231 55.6032.0 52.8294 3.11517 48.7754 5.12082 44.2521 5.12082 39.7287 5.12082 35.7174 3.11517 32.9436.0 30.9806 6.10231 25.2623 10.4977 18.5199 10.4977 17.3677 10.4977 16.2582 10.3697 15.1914 10.1136 15.4048 11.0951 15.4901 12.0766 15.4901 13.1434c0 6.5291-4.0967 12.0766-9.90026 14.2103C8.57699 30.1275 10.412 34.0535 10.412 38.4488 10.412 42.8442 8.53432 46.7702 5.58984 49.544 12.0335 51.2936 16.7703 57.1825 16.7703 64.181 16.7703 65.0344 16.685 65.8879 16.5569 66.7414 17.5811 66.528 18.6479 66.4 19.7148 66.4 26.2865 66.4 31.8341 70.5393 33.9677 76.3003z" fill="#c6eed7"/><path d="M21.038 19.2031C25.3481 20.526 27.5671 24.5373 27.5671 24.5373L24.8787 27.9939C24.8787 27.9939 22.6596 23.9826 18.3496 22.6597L21.038 19.2031z" fill="#00b14f"/><path d="M39.3027 12.3326C40.1989 16.7707 37.6811 20.6113 37.6811 20.6113L33.3711 19.8858C33.3711 19.8858 35.8462 16.0452 34.9927 11.6072L39.3027 12.3326z" fill="#ff7f6b"/><path d="M59.0603 27.0123C63.3276 25.5188 65.3333 21.3794 65.3333 21.3794L62.4742 18.0509C62.4742 18.0509 60.4685 22.1476 56.2012 23.6838L59.0603 27.0123z" fill="#7e5cd6"/><path d="M68.5761 29.9568C72.3314 32.4319 73.3555 36.9126 73.3555 36.9126L69.8136 39.473C69.8136 39.473 68.8321 34.9923 65.0342 32.5172L68.5761 29.9568z" fill="#ff7f6b"/><path d="M58.4627 38.7048 62.0046 36.9552C62.0046 32.0051 50.9949 31.7917 42.3748 31.7491 43.0149 31.109 44.8072 29.4447 45.362 28.8899 48.9465 25.6467 51.635 21.4647 53.4699 16.984 54.7075 14.3383 50.7815 11.9912 49.0319 14.3809 46.4715 17.8802 44.0391 21.2087 40.6679 23.9825L40.6252 24.0251C39.3877 25.1773 29.1887 33.6267 29.0607 33.712 28.4206 34.1388 27.7805 34.6082 27.0123 34.6082H0V53.6406H22.745C23.6838 53.6406 24.5799 53.8539 25.3907 54.2807 28.89 56.0303 37.0406 59.9563 47.965 59.9563 49.8 59.9563 54.7928 58.7187 55.6463 58.676L54.5794 56.6277C55.3049 55.3902 58.4627 38.7048 58.4627 38.7048z" fill="#fbb27e"/><path d="M53.4699 16.9841C52.8298 18.435 52.1897 19.8005 51.4215 21.1661 51.0375 21.8488 50.6534 22.5316 50.2267 23.2571 50.1413 23.4278 50.0133 23.6411 49.8853 23.8118 49.7573 23.9825 49.5866 24.2386 49.4586 24.4092L49.0318 24.964 48.6051 25.4761C47.5383 26.7136 46.4714 27.8231 45.3619 28.9326 44.2524 30.0422 43.1856 31.0663 41.9907 32.1332 40.5825 33.3707 38.4061 33.2427 37.1686 31.8344 35.9311 30.4689 36.0164 28.3779 37.382 27.0977 38.4488 26.0735 39.5583 25.0493 40.6678 24.0679 41.7347 23.0437 42.8442 22.0622 43.8683 21.0807L44.2097 20.7393 44.5084 20.4406C44.5938 20.3553 44.6791 20.2699 44.7645 20.1419 44.8498 20.0139 44.9778 19.8859 45.0632 19.7578 45.4899 19.2031 45.9167 18.6483 46.3434 18.0509 47.1969 16.856 48.1357 15.6612 49.0318 14.4663V14.4236C49.8853 13.3141 51.5069 13.1008 52.6591 13.9542 53.6406 14.6797 53.9393 15.9172 53.4699 16.9841z" fill="#fbb27e"/><path d="M59.4438 39.5156H46.2577C44.8495 39.5156 43.6973 38.3634 43.6973 36.9552 43.6973 35.547 44.8495 34.3948 46.2577 34.3948H59.4438C60.852 34.3948 62.0042 35.547 62.0042 36.9552 62.0042 38.3634 60.852 39.5156 59.4438 39.5156z" fill="#fbb27e"/><path d="M54.9636 58.7187H44.6366C43.2284 58.7187 42.0762 57.5665 42.0762 56.1583 42.0762 54.7501 43.2284 53.5979 44.6366 53.5979H54.9636C56.3718 53.5979 57.524 54.7501 57.524 56.1583 57.524 57.5665 56.3718 58.7187 54.9636 58.7187z" fill="#fbb27e"/><path d="M57.2251 52.3176H43.4842C42.076 52.3176 40.9238 51.1654 40.9238 49.7572 40.9238 48.349 42.076 47.1968 43.4842 47.1968H57.2251C58.6333 47.1968 59.7855 48.349 59.7855 49.7572 59.7855 51.1654 58.6333 52.3176 57.2251 52.3176z" fill="#fbb27e"/><path d="M59.4439 36.9552H46.2578" stroke="#fbb27e" stroke-width="5.12082" stroke-miterlimit="10" stroke-linecap="round"/><path d="M44.6367 56.1583H54.9637" stroke="#fbb27e" stroke-width="5.12082" stroke-miterlimit="10" stroke-linecap="round"/><path d="M43.4844 49.7572H57.2253" stroke="#fbb27e" stroke-width="5.12082" stroke-miterlimit="10" stroke-linecap="round"/><path d="M59.9568 45.9167H44.9784C43.5702 45.9167 42.418 44.7645 42.418 43.3563 42.418 41.9481 43.5702 40.7959 44.9784 40.7959H59.9568C61.365 40.7959 62.5172 41.9481 62.5172 43.3563 62.5172 44.7645 61.365 45.9167 59.9568 45.9167z" fill="#fbb27e"/><path d="M59.9566 43.3563H44.9355" stroke="#fbb27e" stroke-width="5.12082" stroke-miterlimit="10" stroke-linecap="round"/><path d="M48.6055 52.9579H56.0733" stroke="#e48544" stroke-width="1.28021" stroke-miterlimit="10" stroke-linecap="round"/><path d="M57.2254 46.5568H49.2881M50.3549 40.1558H59.9138 50.3549z" stroke="#e48544" stroke-width="1.28021" stroke-miterlimit="10" stroke-linecap="round"/><path d="M34.6082 58.036C30.3835 56.7985 27.2684 55.2622 25.4334 54.3234 24.6226 53.8967 23.6838 53.6833 22.7877 53.6833H0V46.8555H11.6072C18.2643 46.8555 24.1105 44.3378 26.0308 38.0221L27.055 34.6936C27.7805 34.6509 28.4206 34.2242 29.018 33.7974 29.1033 33.7548 32.1332 31.237 35.0776 28.762 33.9254 29.9568 28.8473 35.8884 28.8473 45.746 28.89 52.9152 32.2185 56.4571 34.6082 58.036z" fill="#e28344"/></g><defs><clipPath id="clip0"><path d="M0 0h83v76.3003H0z" fill="#fff"/></clipPath></defs></svg>`;
        GrabWidget.grabGroupLogo = `<svg width="83" height="77" viewBox="0 11 83 77" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M47.012 85.4361C67.4775 85.4361 84.068 68.8455 84.068 48.3801 84.068 27.9146 67.4775 11.3241 47.012 11.3241c-20.4654.0-37.05595 16.5905-37.05595 37.056C9.95605 68.8455 26.5466 85.4361 47.012 85.4361z" fill="#c6eed7"/><mask id="mask0" mask-type="alpha" maskUnits="userSpaceOnUse" x="9" y="0" width="76" height="86"><path d="M47.012 85.4357C67.4775 85.4357 84.068 68.8452 84.068 48.3797 84.068 27.9143 67.8088.912476 47.3434.912476 26.8779.912476 9.95605 27.9143 9.95605 48.3797 9.95605 68.8452 26.5466 85.4357 47.012 85.4357z" fill="#fff"/></mask><g mask="url(#mask0)"><path d="M42.2958 52.0311c0 0-9.3476 8.607799999999997-11.9209 13.5486C29.6029 67.4132 27.2676 67.5998 27.2676 70.1603 27.2676 72.7272 29.3198 89.0549 28.2133 94.1952H51.7979C51.7979 94.1952 59.4793 86.295 62.0719 70.186 62.0719 66.519 58.0446 66.8857 58.0446 66.8857L38.9762 69.073C38.9762 69.073 38.4294 66.3196 41.1829 63.0193 43.9492 59.719 47.0629 54.2313 42.2958 52.0311z" fill="#fdb47e"/><path d="M33.4247 12.0606 60.6377 12.0799C62.7221 12.0799 64.4141 13.656 64.4141 15.5925L64.3819 69.4137C64.3819 71.3501 62.69 72.9263 60.6056 72.9199L33.3926 72.9006C31.3082 72.9006 29.6162 71.3244 29.6162 69.388L29.6484 15.5667C29.6484 13.6239 31.3403 12.0541 33.4247 12.0606z" fill="#f0f2ff"/><path d="M61.2483 67.2979H32.7744C32.4141 67.2979 32.1182 67.0019 32.1182 66.6417V20.3796C32.1182 20.0193 32.4141 19.7234 32.7744 19.7234H61.2483C61.6085 19.7234 61.9045 20.0193 61.9045 20.3796V66.6417C61.9045 67.0084 61.6085 67.2979 61.2483 67.2979z" fill="#fff"/><path fill-rule="evenodd" clip-rule="evenodd" d="M47.28 61.3787C50.833 61.3787 53.7133 58.4984 53.7133 54.9454 53.7133 51.3924 50.833 48.5121 47.28 48.5121 43.727 48.5121 40.8467 51.3924 40.8467 54.9454 40.8467 58.4984 43.727 61.3787 47.28 61.3787z" fill="#cbeedb"/><path fill-rule="evenodd" clip-rule="evenodd" d="M50.5211 52.5669C50.3001 52.3552 49.9517 52.3543 49.7297 52.565L46.7753 55.3683C46.3399 55.7816 45.6581 55.7848 45.2188 55.3756L44.8261 55.01C44.5989 54.7984 44.245 54.804 44.0246 55.0228 43.7934 55.2522 43.7973 55.6273 44.0331 55.8519L45.9724 57.6994C45.9748 57.7016 45.9779 57.7029 45.9812 57.7029 45.9845 57.7029 45.9876 57.7016 45.99 57.6994L50.5193 53.3966C50.7565 53.1712 50.7573 52.7934 50.5211 52.5669z" fill="#805ed7"/><path d="M33.1544 47.2827C33.1544 47.2827 24.7718 56.8362 22.7453 62.0215 22.1727 63.9257 22.7131 66.8207 22.9833 69.3748 23.2535 71.9224 29.0242 87.9864 28.4709 93.2103 31.5911 92.8757 35.5476 86.9635 36.2488 81.6367 37.1817 74.4571 31.6812 64.6012 31.6812 64.6012 31.6812 64.6012 30.8448 61.925 33.2316 58.348 35.6119 54.7775 38.1274 48.9618 33.1544 47.2827z" fill="#fdb47e"/></g><path d="M18.521 46.4749H5.67944C5.31273 46.4749 5.0127 46.1712 5.0127 45.7999V45.125C5.0127 44.7537 5.31273 44.45 5.67944 44.45H18.521C18.8877 44.45 19.1877 44.7537 19.1877 45.125V45.7999C19.1877 46.1712 18.8877 46.4749 18.521 46.4749z" fill="#fff"/><path d="M84.3335 39.3875H71.4919C71.1252 39.3875 70.8252 39.0838 70.8252 38.7125V38.0375C70.8252 37.6663 71.1252 37.3625 71.4919 37.3625H84.3335C84.7002 37.3625 85.0002 37.6663 85.0002 38.0375V38.7125C85.0002 39.0838 84.7002 39.3875 84.3335 39.3875z" fill="#fff"/><path d="M14.4711 51.5375H1.62964C1.26293 51.5375.962891 51.2337.962891 50.8625V50.1875C.962891 49.8162 1.26293 49.5125 1.62964 49.5125H14.4711C14.8378 49.5125 15.1379 49.8162 15.1379 50.1875V50.8625C15.1379 51.2337 14.8378 51.5375 14.4711 51.5375z" fill="#c6eed7"/><path d="M83.3208 32.3H70.4792C70.1125 32.3 69.8125 31.9963 69.8125 31.625V30.95C69.8125 30.5788 70.1125 30.275 70.4792 30.275H83.3208C83.6875 30.275 83.9875 30.5788 83.9875 30.95V31.625C83.9875 31.9963 83.6875 32.3 83.3208 32.3z" fill="#c6eed7"/></svg>`;
        GrabWidget.grabLandingLogo = `<svg width="83" height="77" viewBox="1 5 83 77" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M40.5 5.0625C20.8828 5.0625 5.0625 21.0094 5.0625 40.5c0 19.4906 15.9469 35.4375 35.4375 35.4375 19.4906.0 35.4375-15.9469 35.4375-35.4375.0-19.4906-15.9469-35.4375-35.4375-35.4375z" fill="#c6eed7"/><path d="M26.5695 12.9312C21.2745 7.17476 12.3596 6.80247 6.60316 12.0974.846685 17.3924.474403 26.3073 5.76938 32.0637 10.1113 36.784 17.003 37.8837 22.4691 35.1229L27.9949 35.3536 28.2256 29.8279C31.4326 24.611 30.9113 17.6515 26.5695 12.9312z" fill="url(#paint0_linear)"/><path d="M16.0383 29.9367C20.1517 29.9367 23.4863 26.6021 23.4863 22.4887 23.4863 18.3753 20.1517 15.0407 16.0383 15.0407 11.9249 15.0407 8.59028 18.3753 8.59028 22.4887 8.59028 26.6021 11.9249 29.9367 16.0383 29.9367z" stroke="#fff" stroke-width="1.33897"/><path d="M13.5718 22.4889h2.2995M18.1108 19.0896 15.8513 22.4889 18.1108 19.0896z" stroke="#fff" stroke-width="1.1192" stroke-linecap="round" stroke-linejoin="round"/><path d="M44.2974 68.3438C41.4178 69.9893 36.7036 71.5079 32.2739 72.7735L19.3539 59.8976 24.5126 52.94 58.852 59.8976C58.852 59.8976 47.1769 66.6984 44.2974 68.3438z" fill="#fdb47e"/><path d="M24.9544 60.5894 21.053 62.8419 22.0569 63.9241 26.626 65.5946C28.171 65.2673 37.1786 70.1484 38.6013 69.6093 43.5141 68.6081 44.2966 68.3437 44.2966 68.3437L61.6555 58.5936 35.7641 50.9785 24.9544 60.5894z" fill="#e48544"/><path d="M29.2007 40.0766 54.352 30.4965C55.095 30.274 55.9263 30.7929 56.2258 31.6597L57.3697 34.6629 30.9422 44.7291C30.4213 44.8912 29.8525 44.793 29.3402 44.4675 28.8278 44.1419 28.4416 43.6109 28.2606 43.0018 27.8403 41.8178 28.2503 40.5597 29.2007 40.0766v0z" fill="#242a2e"/><path d="M38.8276 52.5767h27.9253v13.9626H38.8276z" transform="rotate(-72.5625 38.8276 52.5767)" fill="#008e3f"/><path fill-rule="evenodd" clip-rule="evenodd" d="M45.6744 30.7786 46.5366 28.0336 47.1959 25.9346 49.295 26.5939 52.0399 27.4561 58.4178 29.4594 60.5169 30.1187 59.8576 32.2178 53.6701 51.9167 52.8079 54.6616 52.1486 56.7607 50.0496 56.1013 47.3046 55.2392 40.9267 53.2359 38.8276 52.5765 39.487 50.4775 45.6744 30.7786zM47.5858 52.9328C48.2544 51.3688 49.9772 50.4021 51.7233 50.7731L57.7586 31.5586 51.8651 29.7075C51.1965 31.2714 49.4736 32.2381 47.7275 31.8671L41.6922 51.0816 47.5858 52.9328z" fill="#00b14f"/><path d="M52.1489 56.7611h27.9253v.42311H52.1489z" transform="rotate(-72.5625 52.1489 56.7611)" fill="#006a2f"/><path d="M35.6768 50.3506h27.9253v13.9627H35.6768z" transform="rotate(-81.0471 35.6768 50.3506)" fill="#008e3f"/><path fill-rule="evenodd" clip-rule="evenodd" d="M39.2329 27.7807 39.6807 24.9387 40.0231 22.7653 42.1964 23.1077 45.0385 23.5554 51.6422 24.5958 53.8156 24.9382 53.4732 27.1116 50.2599 47.5078 49.8122 50.3499 49.4698 52.5233 47.2964 52.1809 44.4543 51.7331 37.8506 50.6927 35.6772 50.3503 36.0196 48.177 39.2329 27.7807zM44.3918 49.4105C44.8224 47.765 46.3837 46.5547 48.1655 46.664l3.1342-19.8947L45.1976 25.808C44.767 27.4535 43.2057 28.6638 41.4239 28.5545L38.2897 48.4491 44.3918 49.4105z" fill="#00b14f"/><path d="M49.4692 52.5234h27.9253v.42311H49.4692z" transform="rotate(-81.0471 49.4692 52.5234)" fill="#006a2f"/><path d="M36.3892 58.442h27.9253v13.9626H36.3892z" transform="rotate(-97.3021 36.3892 58.442)" fill="#008e3f"/><path fill-rule="evenodd" clip-rule="evenodd" d="M33.4851 35.7792 33.1195 32.9254 32.8398 30.7431 35.0221 30.4634 37.876 30.0977 44.5069 29.2481 46.6892 28.9684 46.9689 31.1507 49.5932 51.6311 49.9589 54.4849 50.2386 56.6672 48.0562 56.9469 45.2024 57.3126 38.5715 58.1622 36.3892 58.4419 36.1095 56.2596 33.4851 35.7792zM44.4924 55.1003C44.4452 53.4 45.6053 51.801 47.3465 51.4073L44.7866 31.4306 38.6593 32.2157C38.7066 33.916 37.5464 35.515 35.8053 35.9087L38.3651 55.8854 44.4924 55.1003z" fill="#00b14f"/><path d="M50.2383 56.6671h27.9253v.423111H50.2383z" transform="rotate(-97.3021 50.2383 56.6671)" fill="#006a2f"/><path d="M28.0962 42.7003C28.0962 42.7003 34.8221 60.7154 36.2137 64.1942 37.6053 67.6731 40.0302 68.0243 43.1712 66.9773L66.2933 58.3692C67.8809 57.6356 68.5835 55.7035 67.8728 53.9984L61.2074 36.4992C60.6072 34.7627 58.7937 33.7782 57.1204 34.2867L30.837 44.298C30.4859 44.3673 28.9785 45.0166 28.0962 42.7003z" fill="#50565e"/><path d="M67.6238 47.7071 64.1073 49.0465C62.8429 49.4566 61.4922 48.7549 61.0856 47.4994L60.7422 46.5979C60.2072 45.3811 60.761 43.9642 61.9689 43.4326L65.4765 42.0965 67.6238 47.7071z" fill="#242a2e"/><path d="M63.7932 47.2554C64.4503 46.9949 64.7729 46.2588 64.5214 45.5984 64.2698 44.9379 63.5337 44.6153 62.8732 44.8668 62.2128 45.1184 61.8812 45.8579 62.1328 46.5184 62.2552 46.8397 62.4957 47.0955 62.8128 47.2303 63.1243 47.3773 63.4843 47.3833 63.7932 47.2554v0z" fill="#50565e"/><path d="M24.7842 62.9466C33.4299 60.0726 35.223 50.7086 35.223 50.7086 35.223 50.7086 36.3756 50.749 41.9257 47.4034 44.6784 45.8142 45.7328 44.2173 46.3189 43.0319 46.7544 42.0747 45.7524 41.8062 44.7831 42.0834 40.9062 43.1925 32.0608 45.4761 29.3812 46.4585 25.8383 47.657 18.2499 60.8781 18.2499 60.8781L21.5589 63.9618" fill="#fdb47e"/><path fill-rule="evenodd" clip-rule="evenodd" d="M29.2539 74.0975C30.8444 73.1979 32.4161 72.4213 33.5391 72.1406L20.4042 60.4946C20.1014 59.9701 19.1844 60.0332 18.1118 60.6525 18.1118 60.6525 16.5351 62.1529 14.0601 64.0323 18.1268 68.5938 23.3487 72.1061 29.2539 74.0975z" fill="#fdb47e"/><defs><linearGradient id="paint0_linear" x1="-.589657" y1="27.6218" x2="23.1307" y2="54.0861" gradientUnits="userSpaceOnUse"><stop stop-color="#7f5dd6"/><stop offset="1" stop-color="#5a37b5"/></linearGradient></defs></svg>`;
        GrabWidget.grabDarkLogo = `<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 6794.7 1185.4"><defs><style>.cls-1{fill:#04b14e;}.cls-2,.cls-3,.cls-4,.cls-5{fill:#7e5cd6;}.cls-2{opacity:0.4;}.cls-3{opacity:0.59;}.cls-4{opacity:0.8;}.cls-6{fill:#292929;}</style></defs><path class="cls-1" d="M4719.83,735.69c0,129.34,97.6,228.53,223.77,228.53,121.4,0,182.5-78.56,182.5-165.05V768.22h-177v47.61H5077.7c.79,46.82-42.06,101.57-134.1,101.57-101.57,0-172.19-75.38-172.19-181.71,0-102.37,76.17-179.34,180.92-179.34,68.24,0,122.2,16.67,172.19,51.58l3.17-2.38V561.12c-37.29-26.19-100.77-51.58-176.95-51.58C4821.4,509.54,4719.83,609.52,4719.83,735.69Zm-73.8,0c0,171.4,127,299.15,297.57,299.15,155.52,0,250.75-97.6,250.75-255.51V698.39h-245.2v46h200.76v42.85c0,123.78-77,200.75-206.31,200.75-142,0-247.58-108.71-247.58-252.33,0-139.66,111.09-250,254.72-250,68.24,0,130.13,16.67,176.95,49.2V488.11c-45.23-32.53-104.74-49.19-176.95-49.19C4778.55,438.92,4646,569.84,4646,735.69Zm649.09,40.47v245.19h46.82V777c0-54.75,35.71-93.63,88.87-93.63,15.87,0,30.15,4,45.23,10.31,6.35-15.08,12.7-27.77,20.63-39.67-16.66-11.11-41.26-18.25-65.07-18.25C5351.46,635.71,5295.12,693.63,5295.12,776.16Zm-69,245.19h46.81V776.16c0-96.81,65.07-162.67,159.5-162.67,31.74,0,64.27,10.31,78.56,19,8.72-10.31,18.25-21.42,30.94-31.74-29.36-20.63-69-32.53-111.88-32.53-124.58,0-203.93,84.11-203.93,208.69Zm734-274.55v69c27.77-37.29,51.58-69.83,73.8-96.81,56.34-69,105.53-107.12,181.71-107.12,103.16,0,178.54,77.76,178.54,187.27,0,107.12-73.8,188.85-175.37,188.85-82.52,0-153.94-56.34-170.6-133.31l-33.33,45.23C6041,975.33,6118,1034.84,6218,1034.84c128.55,0,223-103.16,223-235.67,0-134.1-95.22-234.88-225.36-234.88C6080.69,564.29,6018.8,665.07,5960.08,746.8Zm145.21,34.91h-1.58l-36.51,50c6.35,57.14,64.28,133.31,151.56,133.31,88.88,0,153.15-72.21,153.15-166.63s-65.86-164.26-156.32-164.26c-88.08,0-138.86,64.27-174.57,111.88-17.46,23-47.61,60.31-80.94,107.13V929.3c30.95-46,73-100,103.95-137.27,52.37-62.69,84.11-110.3,150.77-110.3,65.07,0,109.5,50,109.5,116.65,0,69-42.05,119.81-105.54,119.81-66.65,0-113.47-61.1-113.47-129.34Zm-46.81-384.06V595.24c14.28-11.11,30.15-24.6,46.81-31V397.65Zm-69.83,269.8c14.28-18.25,30.15-38.09,46.81-54.76v-215h-46.81ZM5798.21,893.6l-.8-.8c-19.84,15.87-52.37,25.39-83.32,25.39C5650.61,918.19,5603,870.58,5603,800c0-67.45,46.82-119,109.51-119,67.44,0,108.71,49.19,108.71,115v225.36H5868V793.61c0-92-64.27-159.49-154.73-159.49-89.67,0-158.7,67.45-158.7,165.84,0,97.6,68.24,167.43,158.7,165,34.12-.79,69-13.49,84.91-24.6ZM5484,799.17c0,138.07,97.6,235.67,229.32,235.67,29.36,0,63.48-8.73,84.91-25.39V962.63C5783.92,974.53,5749,988,5713.3,988c-107.92,0-180.92-76.17-180.92-188.85,0-107.13,77.76-187.27,180.92-187.27,102.36,0,176.95,75.38,176.95,180.92v228.53h47.61V792c0-131.73-92.84-228.53-224.56-228.53C5584,563.5,5484,665.86,5484,799.17Z"/><circle class="cls-2" cx="326.21" cy="174.4" r="101.27"/><circle class="cls-3" cx="658.49" cy="174.4" r="101.27"/><circle class="cls-4" cx="990.77" cy="174.4" r="101.27"/><circle class="cls-5" cx="1323.05" cy="174.4" r="101.27"/><path class="cls-6" d="M374.8,850.34v175H231.94V498.07H427.15c130.88,0,213.9,55.35,213.9,178.76,0,118.16-82.27,173.51-207.92,173.51Zm0-249.05V751.62h52.35c56.09,0,79.28-24.69,79.28-77,0-51.6-26.93-73.29-81.53-73.29Z"/><path class="cls-6" d="M924.5,979.73c-20.94,29.17-53.85,55.34-112.93,55.34-76.29,0-133.88-41.13-133.88-122.65,0-86,60.58-117.43,161.55-130.89l76.29-9.72c0-52.35-23.19-68.81-77.79-68.81-56.09,0-90.49,14.21-122.65,31.41l-5.24-1.49V636.44c32.16-18.7,81.52-32.16,149.58-32.16,119.67,0,184,46.37,184,167.53v253.54H932.73Zm-9-59.09V847.35l-48.62,6.73c-45.62,6.73-65.07,20.19-65.07,50.11,0,27.67,15.71,46.37,51.61,46.37C881.87,950.56,902.06,938.59,915.53,920.64Z"/><path class="cls-6" d="M1131.67,1078.45c49.36-4.48,74.79-20.19,85.26-46.37l10.47-25.43L1080.06,620v-6h135.38l83.76,241.58,83-241.58h119.67v6L1345.57,1050c-33.65,93.49-87.5,122.66-213.9,130.14Z"/><path class="cls-6" d="M1569.19,498.07h144.35V913.91h187.72v111.44H1569.19Z"/><path class="cls-6" d="M2204.9,979.73c-20.94,29.17-53.84,55.34-112.93,55.34-76.29,0-133.87-41.13-133.87-122.65,0-86,60.58-117.43,161.54-130.89l76.29-9.72c0-52.35-23.19-68.81-77.78-68.81-56.1,0-90.5,14.21-122.66,31.41l-5.24-1.49V636.44c32.16-18.7,81.53-32.16,149.59-32.16,119.66,0,184,46.37,184,167.53v253.54H2213.13Zm-9-59.09V847.35l-48.61,6.73c-45.63,6.73-65.07,20.19-65.07,50.11,0,27.67,15.7,46.37,51.6,46.37C2162.27,950.56,2182.47,938.59,2195.93,920.64Z"/><path class="cls-6" d="M2562.4,1033.58c-71.8,0-127.89-23.19-127.89-109.2V705.25h-46.37V614h46.37V548.18L2563.15,528v86h81.52v91.25h-81.52V899.7c0,27.67,13.46,37.4,40.38,37.4,14.21,0,29.17-3,38.15-5.24l4.48,2.25v89C2626.72,1029.09,2595.31,1033.58,2562.4,1033.58Z"/><path class="cls-6" d="M2925.88,1035.07c-157.81,0-233.35-83-233.35-219.13,0-124.16,81.52-211.66,205.68-211.66,120.41,0,199.69,73.29,184,246.81H2818.93c9.72,62.07,47.12,87.5,118.92,87.5,57.59,0,95-12.71,130.88-29.91l5.24,2.24v93.49C3046.29,1019.37,2996.93,1035.07,2925.88,1035.07ZM2820.42,770.31h144.35c-3-53.1-23.18-79.27-65.81-79.27C2857.07,691,2829.4,715,2820.42,770.31Z"/><path class="cls-6" d="M3281.13,764.33v261H3152.49V614h112.93l9,70.3c21.68-38.14,59.83-77,114.42-77a191,191,0,0,1,32.91,3V731.42l-4.48,3a171.12,171.12,0,0,0-35.9-3.74C3335,730.67,3305.06,738.9,3281.13,764.33Z"/><path class="cls-6" d="M3649.08,1025.35V481.62l99.47-20.19V668.6c27.68-34.41,65.82-53.85,118.17-53.85,102.47,0,174.27,72.55,174.27,205.67,0,136.12-74.8,214.65-183.24,214.65-51.61,0-89.75-20.94-117.42-53.85l-9.73,44.13ZM3841.29,694c-44.12,0-70.3,14.21-92.74,38.89V924.38c23.19,21.69,50.86,32.91,90.5,32.91,58.34,0,101-45.62,101-136.87C3940,731.42,3896.64,694,3841.29,694Z"/><path class="cls-6" d="M4194.3,1044.05l15-37.4L4060.43,629v-5.24h105.45l98,267.75,95.73-267.75h95V629l-162.3,431.54c-32.16,86-81.52,118.17-187,124.9v-83C4156.16,1096.4,4180.84,1078.45,4194.3,1044.05Z"/></svg>`;
        GrabWidget.init = function() {
            var grab_styles = `.grab-price-divider-widget{line-height:1.5}.grab_pdt_container{margin:15px 0}span.grab_pdt_left{font-size:14px;font-family:Roboto,sans-serif;vertical-align:bottom}span.grab_pdt_right img.grab_pdt_logo{width:133px;height:auto;margin:0;position:relative;top:-3px;vertical-align:text-top;display:inline-block}a.grab_pdt_link{font-family:Roboto,sans-serif;color:#000;font-size:10.4px;line-height:normal;text-decoration:underline;font-weight:500;position:relative;top:1px}.grab_pdt_div_modal{position:fixed;background:rgba(0,0,0,.2);top:0;left:0;right:0;bottom:0;display:none;z-index:99}.grab_pdt_div_modal.open{display:block}.grab_pdt_div_modal .grab_pdt_container{position:absolute;max-width:800px;margin:0 auto;left:0;right:0;top:50%;transform:translateY(-50%);background:#fff;padding:18px 40px 32px;box-sizing:border-box;font-family:Roboto,sans-serif}.grab_pdt_container_row_1 h3{font-size:18px;margin:0;display:inline-block}.grab_pdt_container_row_1 h3 strong{font-family:Roboto,sans-serif;font-weight:700}.grab_pdt_container_row_1 img{width:350px;height:auto;vertical-align:middle;margin-left:-10px;position:relative;top:-2px}.grab_pdt_container_row_1{position:relative;padding-top:12px}.grab_pdt_container_row_1:after{position:absolute;content:'';left:0;top:0}.grab_pdt_container_row_2 p{max-width:580px;line-height:30px;font-size:24px;font-weight:400;color:#676767;margin:10px 0 45px}ul.grab_pdt_features{padding:0;list-style:none;margin:0 -20px;display:table}ul.grab_pdt_features li{width:33.33%;float:left;padding:0 20px;box-sizing:border-box;margin:0}ul.grab_pdt_features li img{width:100px;margin-left:20px}.grab_pdt_container_row_3{margin-top:35px}ul.grab_pdt_features li h5{font-size:22px;margin-bottom:14px;margin-top:30px;line-height:30px;color:#4f4f4f;letter-spacing:0}ul.grab_pdt_features li p{font-size:19px;margin:0;line-height:26px;color:#8a8a8a}.grab_pdt_container_row_4{text-align:center;margin-top:40px;border-top:2px solid #ececec;padding-top:30px}a.grab_pdt_action_learnmore{max-width:450px;background:#00b14f;color:#fff;text-decoration:none;display:block;margin:0 auto;border-radius:8px;min-height:60px;font-size:23px;line-height:60px}.grab_pdt_action_bar{position:absolute;right:18px;top:28px;z-index:9}a.grab_pdt_action_close{position:relative;font-size:0;width:30px;height:30px;display:block;cursor:pointer}a.grab_pdt_action_close:after,a.grab_pdt_action_close:before{position:absolute;content:'';background:#676767;width:28px;height:2px;transform:rotate(45deg);top:12px;left:0}a.grab_pdt_action_close:hover::after,a.grab_pdt_action_close:hover::before{background:#000}a.grab_pdt_action_close:before{transform:rotate(-45deg)}a.grab_pdt_action_learnmore:hover{background:#018039;color:#fff}@media screen and (max-width:820px){.grab_pdt_div_modal .grab_pdt_container{width:96%}}@media screen and (max-width:767px){ul.grab_pdt_features{margin:0 -10px}ul.grab_pdt_features li{padding:0 10px}.grab_pdt_container_row_4{margin:40px 0 0}.grab_pdt_container_row_2 p{line-height:26px}.grab_pdt_container_row_1 img{width:300px}.grab_pdt_container_row_2 p{font-size:21px;margin:8px 0 35px}ul.grab_pdt_features li img{width:84px;margin-left:10px}ul.grab_pdt_features li h5{font-size:20px;margin-bottom:12px;margin-top:20px;line-height:27px}ul.grab_pdt_features li p{font-size:18px;line-height:25px}.grab_pdt_container_row_4{margin-top:34px;padding-top:25px}a.grab_pdt_action_learnmore{max-width:400px;min-height:52px;font-size:21px;line-height:52px}a.grab_pdt_action_close:after,a.grab_pdt_action_close:before{width:24px}a.grab_pdt_action_close{width:25px;height:25px}.grab_pdt_action_bar{right:12px}.grab_pdt_div_modal .grab_pdt_container{padding:18px 30px 28px}}@media screen and (max-width:639px){ul.grab_pdt_features li img{width:80px;margin:0}ul.grab_pdt_features li h5{margin-bottom:9px;margin-top:8px}.grab_pdt_container_row_3{margin-top:24px}.grab_pdt_container_row_4{margin:30px 0 0}ul.grab_pdt_features{margin:0}ul.grab_pdt_features li{width:100%;padding:0 0 20px;text-align:center}.grab_pdt_container_row_4{margin:10px 0 0}.grab_pdt_div_modal .grab_pdt_container{height:90%;overflow:auto}}@media screen and (max-width:479px){.grab_pdt_container_row_1 img{width:270px}.grab_pdt_container_row_2 p{font-size:20px}.grab_pdt_div_modal .grab_pdt_container{padding:12px 22px 22px}.grab_pdt_action_bar{right:9px;top:16px}a.grab_pdt_action_close:after,a.grab_pdt_action_close:before{width:21px}a.grab_pdt_action_close{width:20px;height:22px}a.grab_pdt_action_learnmore{min-height:48px;font-size:19px;line-height:48px}.grab_pdt_container_row_4{padding-top:20px}}@media only screen and (max-width:374px){span.grab_pdt_left{font-size:14px}span.grab_pdt_right img.grab_pdt_logo{width:123px;margin:0}}`;
            var head = document.head;
            var link = document.createElement("link");
            link.rel = "stylesheet";
            link.href = "https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap";
            head.appendChild(link);

            var styleSheet = document.createElement("style");
            styleSheet.type = "text/css";
            styleSheet.innerText = grab_styles;
            document.head.appendChild(styleSheet);

            let grab_price_widget_list = document.querySelectorAll(".grab-price-divider-widget");
            if(grab_price_widget_list.length==0){
                document.querySelectorAll(window.grab_widget_product_page_price_selector).forEach(function(e){
                    var grab_widget_placeholder = document.createElement("div");
                    grab_widget_placeholder.className = "grab-price-divider-widget";
                    e.after(grab_widget_placeholder);
                });
                grab_price_widget_list = document.querySelectorAll(".grab-price-divider-widget");
            }

            if(window.grab_widget_visiblity==0){
                return;
            }

            grab_price_widget_list.forEach(function(e){

                if(e.hasAttribute("data-min-price")){
                    var data_min_price = e.getAttribute("data-min-price");
                }else{
                    var data_min_price = 0;
                }
                if(e.hasAttribute("data-max-price")){
                    var data_max_price = parseFloat(e.getAttribute("data-max-price"));
                }else{
                    var data_max_price = 100000000000000;
                }

                if(e.hasAttribute("data-money-format")){
                    var money_format = e.getAttribute("data-money-format");
                }else{
                    if(window.grab_widget_money_format!== undefined){
                        var money_format = window.grab_widget_money_format;
                    }else{
                        var money_format = "${{amount}}";
                    }
                }

                if(window.grab_widget_min>0){
                    data_min_price = parseFloat(window.grab_widget_min);
                }

                if(window.grab_widget_max>0){
                    data_max_price = parseFloat(window.grab_widget_max);
                }

                if(e.hasAttribute("data-product-price")){
                    var data_product_price = parseFloat(e.getAttribute("data-product-price"));
                }else{
                    return;
                }

                if(data_min_price<=data_product_price && data_product_price<=data_max_price && data_product_price>0){
                    var __data_product_price_formated = GrabWidget.formatMoney((data_product_price/GrabWidget.availableInstallment),money_format);
                    e.innerHTML = "<div class=\"grab_pdt_container\"><span class=\"grab_pdt_left\">Or "+ __data_product_price_formated +" x "+GrabWidget.availableInstallment+" with </span><span class=\"grab_pdt_right\">"+ "<img class=\"grab_pdt_logo\" height=\"16px\" src=\""+"data:image/svg+xml;base64,"+ btoa(GrabWidget.grabDarkLogo)+"\" />" + "<a class=\"grab_pdt_link\" href=\"javascript:void(0);\">More info</a>" +"</span></div>";
                }
            });
            let grab_price_widget_link = document.querySelectorAll(".grab-price-divider-widget");
            grab_price_widget_link.forEach(function(e){
                e.addEventListener("click", function() { GrabWidget.openPopup(); });
            });
        };

        GrabWidget.formatMoney = function(cents, format) {
            if (typeof cents == 'string') { cents = cents.replace('.',''); }
            var value = '';
            var placeholderRegex = /\{\{\s*(\w+)\s*\}\}/;
            var formatString = (format || this.money_format);

            function defaultOption(opt, def) {
                return (typeof opt == 'undefined' ? def : opt);
            }

            function formatWithDelimiters(number, precision, thousands, decimal) {
                precision = defaultOption(precision, 2);
                thousands = defaultOption(thousands, ',');
                decimal   = defaultOption(decimal, '.');

                if (isNaN(number) || number == null) { return 0; }

                number = (number).toFixed(precision);

                var parts   = number.split('.'),
                    dollars = parts[0].replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1' + thousands),
                    cents   = parts[1] ? (decimal + parts[1]) : '';

                return dollars + cents;
            }

            switch(formatString.match(placeholderRegex)[1]) {
                case 'amount':
                    value = formatWithDelimiters(cents, 2);
                    break;
                case 'amount_no_decimals':
                    value = formatWithDelimiters(cents, 0);
                    break;
                case 'amount_with_comma_separator':
                    value = formatWithDelimiters(cents, 2, '.', ',');
                    break;
                case 'amount_no_decimals_with_comma_separator':
                    value = formatWithDelimiters(cents, 0, '.', ',');
                    break;
            }

            return formatString.replace(placeholderRegex, value);
        };

        GrabWidget.openPopup = function() {
            var grab_pdt_div_modal = document.createElement("div");
            grab_pdt_div_modal.className = 'grab_pdt_div_modal open';

            var grab_pdt_modal_container = document.createElement("div");
            grab_pdt_modal_container.className = 'grab_pdt_container';
            grab_pdt_div_modal.appendChild(grab_pdt_modal_container);

            var grab_pdt_action_bar = document.createElement("div");
            grab_pdt_action_bar.className = 'grab_pdt_action_bar';
            grab_pdt_modal_container.appendChild(grab_pdt_action_bar);

            var grab_pdt_action_close = document.createElement("a");
            grab_pdt_action_close.className = 'grab_pdt_action_close';
            grab_pdt_action_close.href = 'javascript:void(0)';
            grab_pdt_action_close.text = 'x';
            grab_pdt_action_close.addEventListener("click", function() { GrabWidget.closePopup(); });
            grab_pdt_action_bar.appendChild(grab_pdt_action_close);

            var grab_pdt_container_row_1 = document.createElement("div");
            grab_pdt_container_row_1.className = "grab_pdt_container_row_1";
            grab_pdt_modal_container.appendChild(grab_pdt_container_row_1);

            var container_row_1_img = document.createElement("img");
            container_row_1_img.src = "data:image/svg+xml;base64,"+ btoa(GrabWidget.grabDarkLogo);
            grab_pdt_container_row_1.appendChild(container_row_1_img);

            var grab_pdt_container_row_2 = document.createElement("div");
            grab_pdt_container_row_2.className = "grab_pdt_container_row_2";
            grab_pdt_modal_container.appendChild(grab_pdt_container_row_2);

            var container_row_2_p = document.createElement("p");
            container_row_2_p.innerHTML = "With PayLater, get what you need now and pay later";
            grab_pdt_container_row_2.appendChild(container_row_2_p);

            var grab_pdt_container_row_3 = document.createElement("div");
            grab_pdt_container_row_3.className = "grab_pdt_container_row_3";
            grab_pdt_modal_container.appendChild(grab_pdt_container_row_3);

            var container_row_3_ul = document.createElement("ul");
            container_row_3_ul.className = "grab_pdt_features";

            var row_3_ul_li_1 = document.createElement("li");
            var row_3_ul_li_1_img = document.createElement("img");
            row_3_ul_li_1_img.src = "data:image/svg+xml;base64,"+ btoa(GrabWidget.grabLandingLogo);
            row_3_ul_li_1.appendChild(row_3_ul_li_1_img);
            var row_3_ul_li_1_heading = document.createElement("h5");
            row_3_ul_li_1_heading.innerHTML = 'Spread your bill without interest';
            row_3_ul_li_1.appendChild(row_3_ul_li_1_heading);
            var row_3_ul_li_1_description = document.createElement("p");
            row_3_ul_li_1_description.innerHTML = 'With 4 monthly interest-free instalments, we give you more time to pay in smaller amounts.';
            row_3_ul_li_1.appendChild(row_3_ul_li_1_description);
            container_row_3_ul.appendChild(row_3_ul_li_1);

            var row_3_ul_li_2 = document.createElement("li");
            var row_3_ul_li_2_img = document.createElement("img");
            row_3_ul_li_2_img.src = "data:image/svg+xml;base64,"+ btoa(GrabWidget.grabFrameLogo);
            row_3_ul_li_2.appendChild(row_3_ul_li_2_img);
            var row_3_ul_li_2_heading = document.createElement("h5");
            row_3_ul_li_2_heading.innerHTML = 'No hidden charges, free to use';
            row_3_ul_li_2.appendChild(row_3_ul_li_2_heading);
            var row_3_ul_li_2_description = document.createElement("p");
            row_3_ul_li_2_description.innerHTML = 'Automatic repayments ensures you won&apos;t miss any payments.';
            row_3_ul_li_2.appendChild(row_3_ul_li_2_description);
            container_row_3_ul.appendChild(row_3_ul_li_2);

            var row_3_ul_li_3 = document.createElement("li");
            var row_3_ul_li_3_img = document.createElement("img");
            row_3_ul_li_3_img.src = "data:image/svg+xml;base64,"+ btoa(GrabWidget.grabGroupLogo);
            row_3_ul_li_3.appendChild(row_3_ul_li_3_img);
            var row_3_ul_li_3_heading = document.createElement("h5");
            row_3_ul_li_3_heading.innerHTML = 'Fast and secure Checkout in 2 taps.';
            row_3_ul_li_3.appendChild(row_3_ul_li_3_heading);
            var row_3_ul_li_3_description = document.createElement("p");
            row_3_ul_li_3_description.innerHTML = 'Set up in 2 minutes  without having to save card details on multiple shopping sites anymore.';
            row_3_ul_li_3.appendChild(row_3_ul_li_3_description);
            container_row_3_ul.appendChild(row_3_ul_li_3);

            grab_pdt_container_row_3.appendChild(container_row_3_ul);

            var grab_pdt_container_row_4 = document.createElement("div");
            grab_pdt_container_row_4.className = "grab_pdt_container_row_4";
            grab_pdt_modal_container.appendChild(grab_pdt_container_row_4);

            var grab_pdt_action_learnmore = document.createElement("a");
            grab_pdt_action_learnmore.className = "grab_pdt_action_learnmore";
            grab_pdt_action_learnmore.href = "https://www.grab.com/sg/finance/pay-later/";
            grab_pdt_action_learnmore.text = "Learn more";
            grab_pdt_action_learnmore.target = "_blank";
            grab_pdt_container_row_4.appendChild(grab_pdt_action_learnmore);

            var grab_pdt_div_modal_ctn = document.querySelector(".grab_pdt_div_modal");
            if(grab_pdt_div_modal_ctn!=null){
                document.querySelectorAll(".grab_pdt_div_modal").forEach(function(e){ e.remove(); });
            }
            document.body.appendChild(grab_pdt_div_modal);
        };
        GrabWidget.closePopup = function() {
            document.querySelectorAll(".grab_pdt_div_modal").forEach(function(e){ e.className='grab_pdt_div_modal'; });
        };
        document.addEventListener("DOMContentLoaded", GrabWidget.init());
        return GrabWidget;
    }
});