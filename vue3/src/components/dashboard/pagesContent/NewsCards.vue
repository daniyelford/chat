<template>
  <div class="inner-posts">
    <div class="card-inner" v-if="newsStore.cards.length > 0">
      <div v-for="card in newsStore.cards" :key="card.id" class="card" :class="{ 'my-news': card.self }">
        <div class="user-info">
          <div class="user-data">
            <img v-if="card.user?.image" :src="card.user.image" alt="user" />
            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="#000000" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><g><rect fill="none" height="24" width="24"/></g><g><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/></g></svg>
            <p v-if="card.user?.name || card.user?.family">
              {{ card.user.name }} {{ card.user.family }}
            </p>
          </div>
          <div class="inner-setting-menu">
            <a @click="toggleSetting(card.id)" class="dropdown-settings-menu">
              <svg v-if="activeSettingId === card.id" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="25px" height="25px" viewBox="0,0,256,256"><g fill="none" fill-rule="none" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(0.05905,0.05905)"><path d="M2222,152c1092,0 1978,885 1978,1977c0,1092 -885,1978 -1978,1978c-1092,0 -1978,-885 -1978,-1978c0,-1092 885,-1977 1978,-1977z" fill="#c91603" fill-rule="evenodd"></path><path d="M2736,3031c-8,-4 -16,-8 -26,-13c-98,-55 -180,-70 -251,-75c-46,53 -71,143 -101,250c-38,134 -120,120 -120,120h-86h-7h-86c0,0 -81,14 -120,-120c-31,-108 -56,-199 -104,-252c-70,5 -152,51 -248,105c-122,67 -169,0 -169,0l-61,-61l-5,-5l-61,-61c0,0 -67,-47 0,-169c55,-98 101,-180 105,-251c-53,-46 -143,-71 -250,-101c-134,-38 -120,-120 -120,-120v-86v-7v-86c0,0 -14,-81 120,-120c108,-31 199,-56 252,-104c-5,-70 -51,-152 -105,-248c-67,-122 0,-169 0,-169l61,-61l5,-5l61,-61c0,0 47,-67 169,0c98,55 180,101 251,105c46,-53 71,-143 101,-250c38,-134 120,-120 120,-120h86h7h86c0,0 81,-14 120,120c31,108 56,199 104,252c67,-5 83,-47 174,-97c12,347 127,1111 231,1339c10,22 24,39 43,51c-130,123 -157,154 -175,299v-1zM1805,1848l-1,-1c-87,87 -140,208 -140,341c0,268 217,485 485,485c268,0 485,-217 485,-485c0,-268 -217,-485 -485,-485c-132,0 -251,53 -339,138l1,1l-1,1l-4,4l-1,1v0v-1z" fill="#fcfcfc" fill-rule="evenodd"></path><path d="M2729,1229c0,-178 182,-301 344,-280c162,-21 344,102 344,280c0,316 -122,1150 -231,1390c-20,44 -56,68 -107,70v0h-2h-4h-4h-2v0c-51,-2 -87,-26 -107,-70c-109,-240 -231,-1074 -231,-1390h1zM3067,2839v1c-58,3 -107,26 -149,67c-45,45 -67,100 -67,163c0,73 23,131 70,171c42,36 90,57 146,61v1h5h5v-1c55,-4 104,-24 146,-61c47,-41 70,-98 70,-171c0,-64 -22,-118 -67,-163c-41,-41 -91,-64 -149,-67v-1h-5h-5h1z" fill="#fcfcfc" fill-rule="nonzero"></path><path d="M2150,1742c247,0 448,200 448,448c0,247 -200,448 -448,448c-247,0 -448,-200 -448,-448c0,-247 200,-448 448,-448zM2150,1849c188,0 341,152 341,341c0,188 -152,341 -341,341c-188,0 -341,-152 -341,-341c0,-188 152,-341 341,-341z" fill="#fcfcfc" fill-rule="evenodd"></path></g></g></svg>
              <svg v-else version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32px" height="32px" viewBox="0,0,256,256"><g fill="#317070" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(8,8)"><path d="M28.89,9.58c-0.23,-0.4 -0.49,-0.77 -0.76,-1.12c0.27,-0.35 0.53,-0.73 0.76,-1.13c0.07,-0.11 0.08,-0.25 0.05,-0.37c-0.18,-1.06 -0.73,-2.01 -1.55,-2.7c0,0 0,0 -0.01,-0.01c-0.09,-0.09 -0.19,-0.16 -0.36,-0.14c-0.47,0 -0.92,0.04 -1.36,0.09c-0.17,-0.41 -0.37,-0.82 -0.6,-1.22c-0.06,-0.1 -0.15,-0.18 -0.26,-0.22c-1.03,-0.39 -2.17,-0.39 -3.21,0c-0.11,0.04 -0.2,0.12 -0.26,0.22c-0.23,0.4 -0.43,0.81 -0.6,1.22c-0.44,-0.06 -0.89,-0.09 -1.36,-0.09v0c-0.13,0 -0.26,0.05 -0.35,0.14c-0.82,0.68 -1.37,1.63 -1.56,2.68c-0.04,0.13 -0.03,0.28 0.05,0.4c0.23,0.39 0.49,0.77 0.76,1.13c-0.27,0.35 -0.53,0.73 -0.76,1.12c-0.07,0.12 -0.08,0.25 -0.05,0.38c0.18,1.06 0.73,2.02 1.55,2.7v0c0.09,0.1 0.22,0.15 0.35,0.15v0c0.46,0 0.92,-0.03 1.36,-0.09c0.17,0.42 0.37,0.82 0.6,1.22c0.06,0.1 0.15,0.18 0.26,0.22c0.52,0.2 1.06,0.29 1.6,0.29c0.54,0 1.09,-0.1 1.6,-0.29c0.11,-0.04 0.2,-0.12 0.26,-0.22c0.22,-0.4 0.42,-0.8 0.6,-1.22c0.44,0.06 0.9,0.09 1.36,0.09v0c0.13,0 0.25,-0.05 0.35,-0.14c0.82,-0.68 1.38,-1.64 1.56,-2.69c0.04,-0.13 0.02,-0.27 -0.05,-0.39zM23.21,10.22c-0.97,0 -1.76,-0.79 -1.76,-1.76c0,-0.97 0.79,-1.76 1.76,-1.76c0.97,0 1.76,0.79 1.76,1.76c0,0.97 -0.79,1.76 -1.76,1.76zM20.15,21.87c-0.39,-0.66 -0.82,-1.29 -1.28,-1.87c0.46,-0.58 0.89,-1.21 1.28,-1.87c0.07,-0.12 0.08,-0.25 0.05,-0.38c-0.27,-1.58 -1.08,-3 -2.31,-4.02c0,0 -0.01,-0.01 -0.02,-0.02c-0.09,-0.09 -0.22,-0.15 -0.35,-0.15v0c-0.77,0 -1.53,0.06 -2.26,0.17c-0.28,-0.7 -0.61,-1.38 -0.99,-2.04c-0.06,-0.1 -0.15,-0.18 -0.26,-0.22c-1.53,-0.58 -3.22,-0.58 -4.75,0c-0.11,0.04 -0.2,0.12 -0.26,0.22c-0.38,0.66 -0.71,1.35 -0.98,2.04c-0.73,-0.11 -1.49,-0.16 -2.26,-0.17v0c-0.13,0 -0.26,0.06 -0.35,0.15v0c-1.22,1.01 -2.04,2.43 -2.32,4c-0.04,0.13 -0.03,0.28 0.04,0.41c0.39,0.66 0.82,1.28 1.28,1.87c-0.46,0.59 -0.89,1.21 -1.28,1.87c-0.07,0.12 -0.08,0.26 -0.05,0.38c0.27,1.58 1.08,3 2.31,4.01c0,0 0.01,0.01 0.02,0.02c0.09,0.09 0.22,0.15 0.35,0.15v0c0.77,0 1.53,-0.06 2.26,-0.17c0.28,0.69 0.61,1.38 0.98,2.04c0.06,0.1 0.15,0.18 0.26,0.22c0.76,0.29 1.57,0.43 2.37,0.43c0.8,0 1.61,-0.14 2.37,-0.43c0.11,-0.04 0.2,-0.12 0.26,-0.22c0.38,-0.66 0.71,-1.35 0.99,-2.04c0.73,0.11 1.49,0.16 2.26,0.17v0c0.13,0 0.26,-0.06 0.35,-0.15v0c1.22,-1.01 2.04,-2.43 2.32,-4c0.04,-0.13 0.03,-0.28 -0.05,-0.41zM11.64,23c-1.66,0 -3,-1.34 -3,-3c0,-1.66 1.34,-3 3,-3c1.66,0 3,1.34 3,3c0,1.66 -1.34,3 -3,3z"></path></g></g></svg>
            </a>
            <div v-if="activeSettingId === card.id" class="settings-menu">
              <RouterLink class="choose" :to="{ path: `/show-news/${card.id}` }">
                مشاهده
              </RouterLink>
              <a v-if="card.self && userStore.status==='active'" class="choose" @click="handleEdit(card.id,null)">
                ویرایش
              </a>
              <a
                class="choose"
                v-if="userStore.status==='active' && newsStore.hasRule && !card.self"
                @click="handleReply(card.id)"
              >
                پاسخ
              </a>
              <a
                class="choose"
                v-if="newsStore.hasRule && !card.self && userStore.status==='active'"
                @click="openCalendarModal(card.id,0)"
              >
                قرار ملاقات
              </a>
            </div>
          </div>
        </div>
        <div class="media-inner">
          <MediaSlider v-if="Array.isArray(card.medias) && card.medias.length > 0" :medias="card.medias" />
          <svg v-else version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="100%" viewBox="0,0,256,256"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(2.56,2.56)"><path d="M73,80h-46c-6.075,0 -11,-4.925 -11,-11v-38c0,-6.075 4.925,-11 11,-11h46c6.075,0 11,4.925 11,11v38c0,6.075 -4.925,11 -11,11z" fill="#b2b1c2"></path><path d="M73,76h-46c-3.866,0 -7,-3.134 -7,-7v-38c0,-3.866 3.134,-7 7,-7h46c3.866,0 7,3.134 7,7v38c0,3.866 -3.134,7 -7,7z" fill="#94effe"></path><path d="M73,81h-46c-6.617,0 -12,-5.383 -12,-12v-38c0,-6.617 5.383,-12 12,-12h46c6.617,0 12,5.383 12,12v38c0,6.617 -5.383,12 -12,12zM27,21c-5.514,0 -10,4.486 -10,10v38c0,5.514 4.486,10 10,10h46c5.514,0 10,-4.486 10,-10v-38c0,-5.514 -4.486,-10 -10,-10z" fill="#1f212b"></path><path d="M79.5,69v-4.5l-13.294,-13.294c-0.471,-0.471 -1.089,-0.706 -1.706,-0.706c-0.617,0 -1.235,0.235 -1.706,0.706l-24.294,24.294h34.5c3.59,0 6.5,-2.91 6.5,-6.5z" fill="#52bea0"></path><path d="M73,76h-34.5c-0.202,0 -0.385,-0.122 -0.462,-0.309c-0.077,-0.187 -0.034,-0.402 0.108,-0.545l24.295,-24.294c1.098,-1.099 3.02,-1.099 4.117,0l13.295,13.294c0.094,0.094 0.147,0.221 0.147,0.354v4.5c0,3.86 -3.141,7 -7,7zM39.707,75h33.293c3.309,0 6,-2.691 6,-6v-4.293l-13.148,-13.148c-0.721,-0.721 -1.982,-0.721 -2.703,0z" fill="#1f212b"></path><path d="M27,75.5h40.5l-31.294,-31.294c-0.471,-0.471 -1.089,-0.706 -1.706,-0.706c-0.617,0 -1.235,0.235 -1.706,0.706l-12.294,12.294v12.5c0,3.59 2.91,6.5 6.5,6.5z" fill="#00a6a6"></path><path d="M67.5,76h-40.5c-3.859,0 -7,-3.14 -7,-7v-12.5c0,-0.133 0.053,-0.26 0.146,-0.354l12.295,-12.294c1.098,-1.099 3.02,-1.099 4.117,0l31.295,31.294c0.143,0.143 0.186,0.358 0.108,0.545c-0.078,0.187 -0.259,0.309 -0.461,0.309zM21,56.707v12.293c0,3.309 2.691,6 6,6h39.293l-30.441,-30.441c-0.721,-0.721 -1.982,-0.721 -2.703,0z" fill="#1f212b"></path><path d="M73,76h-46c-3.859,0 -7,-3.14 -7,-7v-38c0,-3.86 3.141,-7 7,-7h41.5c0.276,0 0.5,0.224 0.5,0.5c0,0.276 -0.224,0.5 -0.5,0.5h-41.5c-3.309,0 -6,2.691 -6,6v38c0,3.309 2.691,6 6,6h46c3.309,0 6,-2.691 6,-6v-15.5c0,-0.276 0.224,-0.5 0.5,-0.5c0.276,0 0.5,0.224 0.5,0.5v15.5c0,3.86 -3.141,7 -7,7z" fill="#1f212b"></path><path d="M79.5,44c-0.276,0 -0.5,-0.224 -0.5,-0.5v-2c0,-0.276 0.224,-0.5 0.5,-0.5c0.276,0 0.5,0.224 0.5,0.5v2c0,0.276 -0.224,0.5 -0.5,0.5z" fill="#1f212b"></path><path d="M79.5,51c-0.276,0 -0.5,-0.224 -0.5,-0.5v-4c0,-0.276 0.224,-0.5 0.5,-0.5c0.276,0 0.5,0.224 0.5,0.5v4c0,0.276 -0.224,0.5 -0.5,0.5z" fill="#1f212b"></path><circle cx="66.5" cy="37.5" r="5" fill="#ffe31c"></circle><path d="M66.5,43c-3.032,0 -5.5,-2.467 -5.5,-5.5c0,-3.033 2.468,-5.5 5.5,-5.5c3.032,0 5.5,2.467 5.5,5.5c0,3.033 -2.468,5.5 -5.5,5.5zM66.5,33c-2.481,0 -4.5,2.019 -4.5,4.5c0,2.481 2.019,4.5 4.5,4.5c2.481,0 4.5,-2.019 4.5,-4.5c0,-2.481 -2.019,-4.5 -4.5,-4.5z" fill="#1f212b"></path><path d="M94,95c-0.256,0 -0.512,-0.098 -0.707,-0.293l-88,-88c-0.391,-0.391 -0.391,-1.023 0,-1.414c0.391,-0.391 1.023,-0.391 1.414,0l88,88c0.391,0.391 0.391,1.023 0,1.414c-0.195,0.195 -0.451,0.293 -0.707,0.293z" fill="#1f212b"></path></g></g></svg>
        </div>
        <div class="card-category" v-if="Array.isArray(card.category) && card.category.length > 0">
          <span class="category" v-for="category in card.category" :key="category.id">
            {{ category.title }}
          </span>
        </div>
        <div class="description" v-if="card.description">
          {{ truncateText(card.description) }}
        </div>
        <div style="clear: both;"></div>
        <a class="choose" v-if="card.location?.lat && card.location?.lon" style="margin-top: 10px;" @click="openMapModal(card)">
          {{ card.location?.city?card.location.city:'نمایش روی نقشه' }}
        </a>
        <div class="time">📅 {{ moment(card.created_at).format('jYYYY/jMM/jDD') }}</div>
        <a class="choose" v-if="card.reports && card.reports.length" @click="toggleReports(card.id)">
          {{ showReports[card.id] ? 'بستن پاسخ ها و ملاقات ها' : 'نمایش پاسخ ها و ملاقات ها' }}
        </a>
        <div class="report-block" v-if="card.reports && card.reports.length && showReports[card.id]">
          <div class="single-report" v-for="report in card.reports" :key="report.id">
            <div class="user-info">
              <div class="user-data">
                <img v-if="report.reporter.image" :src="report.reporter.image" alt="reporter image">
                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="#000000" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><g><rect fill="none" height="24" width="24"/></g><g><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/></g></svg>
                <p>{{ report.reporter.name }} {{ report.reporter.family }}</p>
              </div>
              <div class="inner-setting-menu">
                <a @click="toggleReportSetting(report.id)" class="dropdown-settings-menu">
                  <svg v-if="activeReportSettingId === report.id" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="25px" height="25px" viewBox="0,0,256,256"><g fill="none" fill-rule="none" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(0.05905,0.05905)"><path d="M2222,152c1092,0 1978,885 1978,1977c0,1092 -885,1978 -1978,1978c-1092,0 -1978,-885 -1978,-1978c0,-1092 885,-1977 1978,-1977z" fill="#c91603" fill-rule="evenodd"></path><path d="M2736,3031c-8,-4 -16,-8 -26,-13c-98,-55 -180,-70 -251,-75c-46,53 -71,143 -101,250c-38,134 -120,120 -120,120h-86h-7h-86c0,0 -81,14 -120,-120c-31,-108 -56,-199 -104,-252c-70,5 -152,51 -248,105c-122,67 -169,0 -169,0l-61,-61l-5,-5l-61,-61c0,0 -67,-47 0,-169c55,-98 101,-180 105,-251c-53,-46 -143,-71 -250,-101c-134,-38 -120,-120 -120,-120v-86v-7v-86c0,0 -14,-81 120,-120c108,-31 199,-56 252,-104c-5,-70 -51,-152 -105,-248c-67,-122 0,-169 0,-169l61,-61l5,-5l61,-61c0,0 47,-67 169,0c98,55 180,101 251,105c46,-53 71,-143 101,-250c38,-134 120,-120 120,-120h86h7h86c0,0 81,-14 120,120c31,108 56,199 104,252c67,-5 83,-47 174,-97c12,347 127,1111 231,1339c10,22 24,39 43,51c-130,123 -157,154 -175,299v-1zM1805,1848l-1,-1c-87,87 -140,208 -140,341c0,268 217,485 485,485c268,0 485,-217 485,-485c0,-268 -217,-485 -485,-485c-132,0 -251,53 -339,138l1,1l-1,1l-4,4l-1,1v0v-1z" fill="#fcfcfc" fill-rule="evenodd"></path><path d="M2729,1229c0,-178 182,-301 344,-280c162,-21 344,102 344,280c0,316 -122,1150 -231,1390c-20,44 -56,68 -107,70v0h-2h-4h-4h-2v0c-51,-2 -87,-26 -107,-70c-109,-240 -231,-1074 -231,-1390h1zM3067,2839v1c-58,3 -107,26 -149,67c-45,45 -67,100 -67,163c0,73 23,131 70,171c42,36 90,57 146,61v1h5h5v-1c55,-4 104,-24 146,-61c47,-41 70,-98 70,-171c0,-64 -22,-118 -67,-163c-41,-41 -91,-64 -149,-67v-1h-5h-5h1z" fill="#fcfcfc" fill-rule="nonzero"></path><path d="M2150,1742c247,0 448,200 448,448c0,247 -200,448 -448,448c-247,0 -448,-200 -448,-448c0,-247 200,-448 448,-448zM2150,1849c188,0 341,152 341,341c0,188 -152,341 -341,341c-188,0 -341,-152 -341,-341c0,-188 152,-341 341,-341z" fill="#fcfcfc" fill-rule="evenodd"></path></g></g></svg>
                  <svg v-else version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32px" height="32px" viewBox="0,0,256,256"><g fill="#317070" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(8,8)"><path d="M28.89,9.58c-0.23,-0.4 -0.49,-0.77 -0.76,-1.12c0.27,-0.35 0.53,-0.73 0.76,-1.13c0.07,-0.11 0.08,-0.25 0.05,-0.37c-0.18,-1.06 -0.73,-2.01 -1.55,-2.7c0,0 0,0 -0.01,-0.01c-0.09,-0.09 -0.19,-0.16 -0.36,-0.14c-0.47,0 -0.92,0.04 -1.36,0.09c-0.17,-0.41 -0.37,-0.82 -0.6,-1.22c-0.06,-0.1 -0.15,-0.18 -0.26,-0.22c-1.03,-0.39 -2.17,-0.39 -3.21,0c-0.11,0.04 -0.2,0.12 -0.26,0.22c-0.23,0.4 -0.43,0.81 -0.6,1.22c-0.44,-0.06 -0.89,-0.09 -1.36,-0.09v0c-0.13,0 -0.26,0.05 -0.35,0.14c-0.82,0.68 -1.37,1.63 -1.56,2.68c-0.04,0.13 -0.03,0.28 0.05,0.4c0.23,0.39 0.49,0.77 0.76,1.13c-0.27,0.35 -0.53,0.73 -0.76,1.12c-0.07,0.12 -0.08,0.25 -0.05,0.38c0.18,1.06 0.73,2.02 1.55,2.7v0c0.09,0.1 0.22,0.15 0.35,0.15v0c0.46,0 0.92,-0.03 1.36,-0.09c0.17,0.42 0.37,0.82 0.6,1.22c0.06,0.1 0.15,0.18 0.26,0.22c0.52,0.2 1.06,0.29 1.6,0.29c0.54,0 1.09,-0.1 1.6,-0.29c0.11,-0.04 0.2,-0.12 0.26,-0.22c0.22,-0.4 0.42,-0.8 0.6,-1.22c0.44,0.06 0.9,0.09 1.36,0.09v0c0.13,0 0.25,-0.05 0.35,-0.14c0.82,-0.68 1.38,-1.64 1.56,-2.69c0.04,-0.13 0.02,-0.27 -0.05,-0.39zM23.21,10.22c-0.97,0 -1.76,-0.79 -1.76,-1.76c0,-0.97 0.79,-1.76 1.76,-1.76c0.97,0 1.76,0.79 1.76,1.76c0,0.97 -0.79,1.76 -1.76,1.76zM20.15,21.87c-0.39,-0.66 -0.82,-1.29 -1.28,-1.87c0.46,-0.58 0.89,-1.21 1.28,-1.87c0.07,-0.12 0.08,-0.25 0.05,-0.38c-0.27,-1.58 -1.08,-3 -2.31,-4.02c0,0 -0.01,-0.01 -0.02,-0.02c-0.09,-0.09 -0.22,-0.15 -0.35,-0.15v0c-0.77,0 -1.53,0.06 -2.26,0.17c-0.28,-0.7 -0.61,-1.38 -0.99,-2.04c-0.06,-0.1 -0.15,-0.18 -0.26,-0.22c-1.53,-0.58 -3.22,-0.58 -4.75,0c-0.11,0.04 -0.2,0.12 -0.26,0.22c-0.38,0.66 -0.71,1.35 -0.98,2.04c-0.73,-0.11 -1.49,-0.16 -2.26,-0.17v0c-0.13,0 -0.26,0.06 -0.35,0.15v0c-1.22,1.01 -2.04,2.43 -2.32,4c-0.04,0.13 -0.03,0.28 0.04,0.41c0.39,0.66 0.82,1.28 1.28,1.87c-0.46,0.59 -0.89,1.21 -1.28,1.87c-0.07,0.12 -0.08,0.26 -0.05,0.38c0.27,1.58 1.08,3 2.31,4.01c0,0 0.01,0.01 0.02,0.02c0.09,0.09 0.22,0.15 0.35,0.15v0c0.77,0 1.53,-0.06 2.26,-0.17c0.28,0.69 0.61,1.38 0.98,2.04c0.06,0.1 0.15,0.18 0.26,0.22c0.76,0.29 1.57,0.43 2.37,0.43c0.8,0 1.61,-0.14 2.37,-0.43c0.11,-0.04 0.2,-0.12 0.26,-0.22c0.38,-0.66 0.71,-1.35 0.99,-2.04c0.73,0.11 1.49,0.16 2.26,0.17v0c0.13,0 0.26,-0.06 0.35,-0.15v0c1.22,-1.01 2.04,-2.43 2.32,-4c0.04,-0.13 0.03,-0.28 -0.05,-0.41zM11.64,23c-1.66,0 -3,-1.34 -3,-3c0,-1.66 1.34,-3 3,-3c1.66,0 3,1.34 3,3c0,1.66 -1.34,3 -3,3z"></path></g></g></svg>
                </a>
                <div class="settings-menu" v-if="activeReportSettingId==report.id">
                  <a v-if="userStore.status==='active' && report.reporter.self && report.description" class="choose" @click="handleEdit(card.id,report.id)">
                    ویرایش
                  </a>
                  <a
                  class="choose"
                  v-if="userStore.status==='active' && report.reporter.self && report.run_time"
                  @click="openCalendarModal(card.id,report.id)">
                    ویرایش زمان ملاقات
                  </a>
                  <RouterLink class="choose" :to="{ path: `/show-cartable/${report.id}` }">
                    مشاهده
                  </RouterLink>
                  <a
                    class="choose"
                    v-if="userStore.status==='active' && report.reporter.self && !report.run_time"
                    @click="openCalendarModal(card.id,report.id)"
                  >
                    قرار ملاقات
                  </a>
                </div>
              </div>
            </div>
            <div class="media-inner">
              <MediaSlider v-if="Array.isArray(report.media) && report.media.length > 0" :medias="report.media" />
              <svg v-else version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="100%" viewBox="0,0,256,256"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(2.56,2.56)"><path d="M73,80h-46c-6.075,0 -11,-4.925 -11,-11v-38c0,-6.075 4.925,-11 11,-11h46c6.075,0 11,4.925 11,11v38c0,6.075 -4.925,11 -11,11z" fill="#b2b1c2"></path><path d="M73,76h-46c-3.866,0 -7,-3.134 -7,-7v-38c0,-3.866 3.134,-7 7,-7h46c3.866,0 7,3.134 7,7v38c0,3.866 -3.134,7 -7,7z" fill="#94effe"></path><path d="M73,81h-46c-6.617,0 -12,-5.383 -12,-12v-38c0,-6.617 5.383,-12 12,-12h46c6.617,0 12,5.383 12,12v38c0,6.617 -5.383,12 -12,12zM27,21c-5.514,0 -10,4.486 -10,10v38c0,5.514 4.486,10 10,10h46c5.514,0 10,-4.486 10,-10v-38c0,-5.514 -4.486,-10 -10,-10z" fill="#1f212b"></path><path d="M79.5,69v-4.5l-13.294,-13.294c-0.471,-0.471 -1.089,-0.706 -1.706,-0.706c-0.617,0 -1.235,0.235 -1.706,0.706l-24.294,24.294h34.5c3.59,0 6.5,-2.91 6.5,-6.5z" fill="#52bea0"></path><path d="M73,76h-34.5c-0.202,0 -0.385,-0.122 -0.462,-0.309c-0.077,-0.187 -0.034,-0.402 0.108,-0.545l24.295,-24.294c1.098,-1.099 3.02,-1.099 4.117,0l13.295,13.294c0.094,0.094 0.147,0.221 0.147,0.354v4.5c0,3.86 -3.141,7 -7,7zM39.707,75h33.293c3.309,0 6,-2.691 6,-6v-4.293l-13.148,-13.148c-0.721,-0.721 -1.982,-0.721 -2.703,0z" fill="#1f212b"></path><path d="M27,75.5h40.5l-31.294,-31.294c-0.471,-0.471 -1.089,-0.706 -1.706,-0.706c-0.617,0 -1.235,0.235 -1.706,0.706l-12.294,12.294v12.5c0,3.59 2.91,6.5 6.5,6.5z" fill="#00a6a6"></path><path d="M67.5,76h-40.5c-3.859,0 -7,-3.14 -7,-7v-12.5c0,-0.133 0.053,-0.26 0.146,-0.354l12.295,-12.294c1.098,-1.099 3.02,-1.099 4.117,0l31.295,31.294c0.143,0.143 0.186,0.358 0.108,0.545c-0.078,0.187 -0.259,0.309 -0.461,0.309zM21,56.707v12.293c0,3.309 2.691,6 6,6h39.293l-30.441,-30.441c-0.721,-0.721 -1.982,-0.721 -2.703,0z" fill="#1f212b"></path><path d="M73,76h-46c-3.859,0 -7,-3.14 -7,-7v-38c0,-3.86 3.141,-7 7,-7h41.5c0.276,0 0.5,0.224 0.5,0.5c0,0.276 -0.224,0.5 -0.5,0.5h-41.5c-3.309,0 -6,2.691 -6,6v38c0,3.309 2.691,6 6,6h46c3.309,0 6,-2.691 6,-6v-15.5c0,-0.276 0.224,-0.5 0.5,-0.5c0.276,0 0.5,0.224 0.5,0.5v15.5c0,3.86 -3.141,7 -7,7z" fill="#1f212b"></path><path d="M79.5,44c-0.276,0 -0.5,-0.224 -0.5,-0.5v-2c0,-0.276 0.224,-0.5 0.5,-0.5c0.276,0 0.5,0.224 0.5,0.5v2c0,0.276 -0.224,0.5 -0.5,0.5z" fill="#1f212b"></path><path d="M79.5,51c-0.276,0 -0.5,-0.224 -0.5,-0.5v-4c0,-0.276 0.224,-0.5 0.5,-0.5c0.276,0 0.5,0.224 0.5,0.5v4c0,0.276 -0.224,0.5 -0.5,0.5z" fill="#1f212b"></path><circle cx="66.5" cy="37.5" r="5" fill="#ffe31c"></circle><path d="M66.5,43c-3.032,0 -5.5,-2.467 -5.5,-5.5c0,-3.033 2.468,-5.5 5.5,-5.5c3.032,0 5.5,2.467 5.5,5.5c0,3.033 -2.468,5.5 -5.5,5.5zM66.5,33c-2.481,0 -4.5,2.019 -4.5,4.5c0,2.481 2.019,4.5 4.5,4.5c2.481,0 4.5,-2.019 4.5,-4.5c0,-2.481 -2.019,-4.5 -4.5,-4.5z" fill="#1f212b"></path><path d="M94,95c-0.256,0 -0.512,-0.098 -0.707,-0.293l-88,-88c-0.391,-0.391 -0.391,-1.023 0,-1.414c0.391,-0.391 1.023,-0.391 1.414,0l88,88c0.391,0.391 0.391,1.023 0,1.414c-0.195,0.195 -0.451,0.293 -0.707,0.293z" fill="#1f212b"></path></g></g></svg>
            </div>
            <p v-if="report.description" class="description">📄 {{ truncateText(report.description) }}</p>
            <div style="clear: both;"></div>
            <a
              class="choose"
              v-if="report.location?.lat && report.location?.lon"
              @click="openMapModal(report, card)"
              style="margin-top: 10px;"
              >
              {{ report.location?.city?report.location?.city:'نمایش روی نقشه' }}
            </a>
            <p v-if="report.run_time">
              📅 تاریخ ملاقات {{ moment(report.run_time).format('jYYYY/jMM/jDD') }}
            </p>
            <div class="time">📅 {{ moment(report.created_at).format('jYYYY/jMM/jDD') }}</div>
          </div>
        </div>
      </div>
      <div :ref="newsScroll.el" v-if="newsScroll.loadMore" class="scroll-trigger"></div>
    </div>
    <div class="loading" v-else style="
    font-style: italic;
    text-align: center;
    padding: 20px;
    font-weight: 700;
    color: #888;
    border-radius: 10px;
    box-shadow: 0 0 10px grey;
    background: #e0d4e3;
    margin-top: 10px;">
      <!-- <div class="tiny-loader"></div> -->
       <RadarAnimation/>
    </div>
    <!-- <div v-else class="none-cart-error">
      <span>
        خبری در محدوده شما وجود ندارد
      </span>
    </div> -->
    <span v-if="toastMsg" class="toast">
      {{ toastMsg }}
    </span>
    <CalendarModal
    v-if="showModal"
    :initialDate="modalRunTime"
    @close="showModal = false"
    @submit="onCalendarSubmit"
    />
  </div>
  <AddNewsForm v-if="userStore.status==='active'"
  :reply-to-id="replyToId"
  :edit-data="editCard"
  :edit-report="editReport"
  @clearReplyId="replyToId = 0; editCard = null; editReport = null"
  />
  <div v-else-if="userStore.status==='inactive'" class="ban">
    دسترسی شما غیر فعال است
    <span v-if="userStore.banTime">
      تاریخ این اقدام
      {{ moment(userStore.banTime).format('jYYYY/jMM/jDD') }}
    </span>
  </div>
  <div v-else-if="userStore.infoIsLoaded" class="load">
    <div class="tiny-loader"></div>
  </div>
  <BaseModal :show="showMapModal" @close="showMapModal = false">
    <SinglePlaceMap
    v-if="selectedPlace && selectedPlace.lat && selectedPlace.lon && userCoordinate && userCoordinate.lat && userCoordinate.lon"
    :user-center="userCoordinate"
    :place="selectedPlace"
    />
  </BaseModal>
</template>
<script setup>
  import { ref, onMounted } from 'vue'
  import moment from 'moment-jalaali'
  import BaseModal from '@/components/tooles/modal/BaseModal.vue'
  import SinglePlaceMap from '@/components/tooles/places/SinglePlaceMap.vue'
  import MediaSlider from '@/components/tooles/media/MediaSlider.vue'
  import CalendarModal from '@/components/tooles/news/CalendarModal.vue'
  import { polling } from '@/composables/polling'
  import { scroll } from '@/composables/scroll'
  import AddNewsForm from '@/components/dashboard/pagesContent/AddNewsForm.vue'
  import { useNewsStore } from '@/stores/news'
  import { useUserStore } from '@/stores/user'
  import RadarAnimation from '@/components/tooles/RadarAnimation.vue'
  const toastMsg = ref('')
  const selectedNewsId = ref(null) 
  const selectedReportId = ref(null)
  const showModal = ref(false)
  const modalRunTime = ref(null)
  const showReports = ref({})
  const replyToId = ref(0)
  const editCard = ref(null)
  const editReport = ref(null)
  const userCoordinate = ref(null)
  const showMapModal = ref(false)
  const selectedPlace = ref(null)
  const activeSettingId = ref(null)
  const activeReportSettingId = ref(null)
  const newsStore = useNewsStore()
  const userStore = useUserStore()
  const toggleSetting = (id) => {
    activeSettingId.value = activeSettingId.value === id ? null : id
  }
  const toggleReportSetting = (id) => {
    activeReportSettingId.value = activeReportSettingId.value === id ? null : id
  }
  const truncateText = (text, max = 50) => {
    if (!text) return ''
    return text.length > max ? text.slice(0, max) + '...' : text
  }
  function showToast(msg) {
    toastMsg.value = msg
    setTimeout(() => (toastMsg.value = ''), 3000)
  }
  function toggleReports(id) {
    showReports.value[id] = !showReports.value[id]
  }
  function handleEdit(newsId, reportId) {
    const card = newsStore.cards.find(c => c.id === newsId)
    editCard.value = card
    replyToId.value = 0
    if (reportId) {
      editReport.value = card.reports.find(r => r.id === reportId) || null
    } else {
      editReport.value = null
    }
  }
  function handleReply(newsId) {
    replyToId.value = newsId
  }
  const fetchNews = async () => {
    const ok = await newsStore.fetchNews({
      limit: 10,
      offset: newsStore.cards.length,
      append: true,
    })
    if (ok) {
      return {
        items: newsStore.cards,
        has_more: newsStore.more,
      }
    }
    return {
      items: newsStore.cards,
      has_more: false,
    }
  }
  const newsScroll = scroll(fetchNews, {
    limit: 10,
    immediate: true,
  })
  polling(() => newsStore.fetchLatestNewsRaw(newsStore.cards.length>0?newsStore.cards.length:10, 0), {
    intervalMs: 6000,
    isDifferent: (oldData, newData) => {
      if (!Array.isArray(oldData) || !Array.isArray(newData)) return true
      if (oldData.length !== newData.length) return true
      for (let i = 0; i < newData.length; i++) {
        const oldItem = oldData[i]
        const newItem = newData[i]
        if (oldItem.id !== newItem.id) return true
        if (oldItem.show_status !== newItem.show_status) return true
        if (oldItem.user.name !== newItem.user.name) return true
        if (oldItem.user.family !== newItem.user.family) return true
        if (oldItem.user.image !== newItem.user.image) return true
        if (oldItem.location.address !== newItem.location.address) return true
        if (oldItem.medias.length !== newItem.medias.length) return true
        for (let a = 0; a < newItem.medias.length; a++) {
          if(newItem.medias[a].id!==oldItem.medias[a].id) return true
          if(newItem.medias[a].type!==oldItem.medias[a].type) return true
          if(newItem.medias[a].url!==oldItem.medias[a].url) return true
        }
        const oldReports = oldItem.reports || []
        const newReports = newItem.reports || []
        if (oldReports.length !== newReports.length) return true
        for (let j = 0; j < newReports.length; j++) {
          const oldReport = oldReports[j]
          const newReport = newReports[j]
          if (
            oldReport.id !== newReport.id ||
            oldReport.status !== newReport.status ||
            oldReport.description !== newReport.description ||
            oldReport.run_time !== newReport.run_time
          ) return true
          if(oldReport.reporter.name !== newReport.reporter.name) return true
          if(oldReport.reporter.family !== newReport.reporter.family) return true
          if(oldReport.reporter.image !== newReport.reporter.image) return true
          if (oldReport.media.length !== newReport.media.length) return true
          for (let a = 0; a < newReport.media.length; a++) {
            if(newReport.media[a].id!==oldReport.media[a].id) return true
            if(newReport.media[a].type!==oldReport.media[a].type) return true
            if(newReport.media[a].url!==oldReport.media[a].url) return true
          }
        }
      }
      return false
    },
    onChange: async (newCards) => {
      newsStore.cards = newsStore.cards.filter(card =>
        newCards.some(newItem => Number(newItem.id) === Number(card.id))
      )
      for (const newItem of newCards) {
        const index = newsStore.cards.findIndex(c => Number(c.id) === Number(newItem.id))
        if (index !== -1) {
          newsStore.cards.splice(index, 1, newItem)
        } else {
          newsStore.cards.push(newItem)
        }
      }
      if (newCards.length > 0) {
        showToast('منتظر بمانید')
      }
    }
  })
  function openCalendarModal(id,reportId) {
      selectedNewsId.value = id
      selectedReportId.value=reportId
      if (reportId) {
        const card = newsStore.cards.find(c => c.id === id)
        if (card) {
          const report = card.reports.find(r => r.id === reportId)
          modalRunTime.value = report?.run_time ? new Date(report.run_time) : null
        } else {
          modalRunTime.value = null
        }
      } else {
        modalRunTime.value = null
      }
      showModal.value = true
  }
  async function onCalendarSubmit({ date }) {
      const jsDate = date ? moment(date, 'jYYYY/jMM/jDD').hour(12).minute(0).second(0).toDate() : null
      const success = await newsStore.scheduleNewsRunTime(selectedNewsId.value,selectedReportId.value,jsDate)
      if (success) {
          showModal.value = false
          const updated = await newsStore.fetchNewsById(selectedNewsId.value)
          if (updated) {
            const index = newsStore.cards.findIndex(c => c.id === selectedNewsId.value)
            if (index !== -1) {
              newsStore.cards[index] = updated
            }
          }
      } else {
          alert('خطا در ثبت تاریخ اجرا')
      }
  }
  function openMapModal(item, parentCard = null) {
    const lat = item?.location?.lat
    const lon = item?.location?.lon
    if (!lat || !lon) return
    if(parentCard){
      selectedPlace.value = {
        ...item.location,
        title: (item.run_time?'📅 تاریخ ملاقات '+ moment(item.run_time).format('jYYYY/jMM/jDD') : ''),
        description: (item.description ? item.description : 'بدون عنوان'),
        categories: parentCard?.category || [],
        medias: item.media || [],
        address: item.location.address
      }
    }else{
      selectedPlace.value = {
        ...item.location,
        title:'موقعیت',
        description: item.description || 'بدون عنوان',
        categories: item?.category || [],
        medias: item.medias || [],
        address: item.location.address
      }
    }
    showMapModal.value = true
  }
  onMounted(async () => {
    const res = await newsStore.fetchAddNewsData()
    await userStore.fetchUserInfo()
    if (res?.coordinate?.lat && res?.coordinate?.lon) {
      userCoordinate.value = res.coordinate
    }
  })
</script>
<style scoped>
  .ban,.load{
    position: fixed;
    border-radius: 45px 45px 0 0;
    bottom: 0;
    left: 0;
    right: 0;
    height: 55px;
    text-align: center;
    color: white;
    font-size: 16.5px;
    padding-top: 10px;
    font-weight: bolder;
    box-sizing: border-box;
  }
  .ban{
    background: red;
  }
  .load{
    background: rgb(122, 6, 122);
  }
  .inner-posts {
    position: fixed;
    top: 60px;
    bottom: 45px;
    overflow: hidden;
    left: 30px;
    right: 30px;
  }
  .scroll-trigger{
    position: relative;
    top: 10px;
  }
  .card-inner {
    padding: 10px;
    width: 100%;
    box-sizing: border-box;
    display: flex;
    flex-direction: column-reverse;
    max-height: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    gap: 3px;
    justify-content: flex-start;
  }
  .card {
    box-shadow: 0 5px 5px grey;
    border-left: 5px solid #e17cfd;
    box-sizing: border-box;
    width: 100%;
    padding: 0.5rem 2rem 2rem;
    border-radius: 0 50px 50px 50px;
    background-color: #fff6c1;
    align-self: flex-start;
    position: sticky;
    bottom: 10px;
    direction: rtl;
    transition: all 0.3s ease;
  }
  .card .user-info.my-news {
    text-align: right;
  }
  .card.my-news {
    border-right: 5px solid #0a7283;
    border-left: unset;
    align-self: flex-end;
    border-radius: 50px 0 50px 50px;
    background-color: #b3ffd0;
  }
  .user-data{
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  .user-info {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    justify-content: space-between;
  }
  .inner-setting-menu {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
  }
  .settings-menu {
    display: flex;
    box-sizing: border-box;
    width: 100px;
    gap: 5px;
    background: #fff;
    border-radius: 10px;
    position: absolute;
    top: 45px;
    align-items: stretch;
    padding: 10px 5px;
    flex-direction: column;
    text-align: center;
  }
  .settings-menu .choose{
    margin: 0 !important;
  }
  .user-data img ,.user-data svg {
    width: 45px;
    height: 45px;
    object-fit: cover;
    border-radius: 50%;
  }
  .card-category {
    margin-top: 0.5rem;
    display: flex;
    flex-wrap: wrap;
    gap: 0.3rem;
  }
  .card-category .category {
    background: #eee;
    padding: 2px 6px;
    border-radius: 6px;
    font-size: 0.75rem;
  }
  .description {
    margin-top: .5rem;
    max-height: 100px;
    background: lightsalmon;
    overflow: auto;
    padding: 10px;
    box-sizing: border-box;
    border-radius: 10px;
  }
  .time {
    font-size: 0.75rem;
    color: #777;
    float: left;
    margin-top: 13px;
  }
  .choose {
    display: inline-block;
    margin-right: 0.2rem;
    margin-top: 0.5rem;
    background: #007bff;
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.8rem;
    cursor: pointer;
    text-decoration: none;
  }
  .choose:hover {
    background: #0056b3;
  }
  .report-block {
    margin-top: 1rem;
    padding-top: 0.5rem;
    border-top: 1px solid #ccc;
    display: flex;
    gap: 5px;
    justify-content: flex-start;
    flex-direction: row;
    align-items: stretch;
    overflow: auto;
  }
  .single-report {
    margin-top: 0.5rem;
    position: sticky;
    right: 7px;
    min-width: 70%;
    font-size: 0.9rem;
    background: #fafafa;
    padding: 10px 5px;
    border-radius: 0.5rem;
    border-right: 5px solid #32807f;
  }
  .none-cart-error{
    text-align: center;
    background-color: #dc6a6a;
    position: fixed;
    bottom: calc(50% - 150px);
    top: calc(50% - 150px);
    left: calc(50% - 150px);
    right: calc(50% - 150px);
    height: 300px;
    overflow: hidden;
    width: 300px;
    font-size: x-large;
    border-radius: 50%;
    font-weight: bolder;
    box-sizing: border-box;
    box-shadow: 0 0 20px #1e1212;
  }
  .none-cart-error span{
    display: block;
    background: #fff;
    top: 40%;
    height: 20%;
    box-sizing: border-box;
    color: #000;
    position: relative;
    padding-top: 5%;
  }
  .toast{
    position: fixed;
    z-index: 99999999999999;
    padding: 10px;
    background-color: greenyellow;
    border-radius: 10px;
    bottom: 85px;
    left: 15px;
  }
  .tiny-loader {
    width: 20px;
    height: 20px;
    border: 2px solid #ccc;
    border-top-color: #333;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 10px auto;
  }
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
</style>