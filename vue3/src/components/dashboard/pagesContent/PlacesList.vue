<template>
  <div class="map-in" :class="{ expanded: mapExpanded }">
    <StaticMapWithMarkers
    :userCenter="center"
    :placeCenter="mapCenter"
    :markers="markers"
    :activePlaceId="activePlaceId"
    />
  </div>
  <button @click="mapExpanded=!mapExpanded" class="layout-toggle">
    <svg v-if="mapExpanded" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="none" stroke-linecap="none" stroke-linejoin="none" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(2.56,2.56)"><path d="M17.5,92.536c-1.371,0 -2.684,-0.423 -3.795,-1.223c-1.688,-1.207 -2.705,-3.183 -2.705,-5.277v-58.264c0,-2.788 1.774,-5.263 4.415,-6.156l21.499,-7.283c0.673,-0.229 1.374,-0.344 2.084,-0.344c0.674,0 1.342,0.104 1.983,0.31l14.177,4.569c4.011,-7.744 12.529,-11.868 19.808,-11.868c10.657,0 22.083,8.529 22.104,21.227c0.008,5.13 -2.287,10.998 -7.008,17.906v32.62c0,2.789 -1.775,5.264 -4.418,6.157l-21.542,7.283c-0.688,0.229 -1.381,0.342 -2.083,0.342c-0.664,0 -1.321,-0.101 -1.955,-0.301l-20.966,-6.643l-19.516,6.602c-0.675,0.228 -1.374,0.343 -2.082,0.343z" fill="#000000" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" opacity="0.35"></path><path d="M15.5,90.536c-1.371,0 -2.684,-0.423 -3.795,-1.223c-1.688,-1.207 -2.705,-3.183 -2.705,-5.277v-58.264c0,-2.788 1.774,-5.263 4.415,-6.156l21.499,-7.283c0.673,-0.229 1.374,-0.344 2.084,-0.344c0.674,0 1.342,0.104 1.983,0.31l14.177,4.569c4.011,-7.744 12.529,-11.868 19.808,-11.868c10.657,0 22.083,8.529 22.104,21.227c0.008,5.13 -2.287,10.998 -7.008,17.906v32.62c0,2.789 -1.775,5.264 -4.418,6.157l-21.542,7.283c-0.688,0.229 -1.381,0.342 -2.083,0.342c-0.664,0 -1.321,-0.101 -1.955,-0.301l-20.966,-6.643l-19.516,6.602c-0.675,0.228 -1.374,0.343 -2.082,0.343z" fill="#f2f2f2" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter"></path><path d="M59.542,25.772l-22.021,-7.283l-22.021,7.283v58.264l22.021,-7.283l22.5,7.283l21.542,-7.283v-58.264z" fill="#d9eeff" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter"></path><path d="M36.914,71.824l-16.914,5.74v-48.457l17.1,-5.74l22.51,7.335l17.39,-5.74v48.457l-17.418,5.652z" fill="#70bfff" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter"></path><path d="M60,84.807l-23,-8.199v-57.926l23,7.218z" fill="#000000" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" opacity="0.35"></path><path d="M73,12.667c-6.959,0 -14.222,5.526 -14.234,13.443c-0.014,9.474 14.234,23.723 14.234,23.723c0,0 14.249,-14.229 14.234,-23.723c-0.013,-7.906 -7.275,-13.443 -14.234,-13.443z" fill="#3fb044" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter"></path><path d="M88.571,26.238c-0.014,-8.667 -7.976,-14.738 -15.605,-14.738c-7.281,0 -14.849,5.525 -15.536,13.574l-20.43,-6.585l-21.5,7.283v58.264l21.532,-7.283l22.988,7.283l21.542,-7.283v-34.678c3.624,-5.003 7.016,-10.984 7.009,-15.837z" fill="none" stroke="#40396e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path><circle cx="73" cy="27.029" r="6.998" fill="#f2f2f2" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter"></circle></g></g></svg>
    <svg v-else version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256"><g fill="#000000" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(4.26667,4.26667)"><path d="M23.9707,5.69141c-1.28086,-0.00227 -2.56034,0.00739 -3.83984,0.02539c-1.973,0.028 -4.65791,-0.27253 -6.12891,1.35547c-0.736,0.815 -0.97584,1.86278 -1.08984,2.92578c-0.134,1.25 -0.1718,2.51648 -0.2168,3.77148c-0.365,10.215 0.79872,20.39575 1.13672,30.59375c0.042,1.27 0.07194,2.5415 0.08594,3.8125c0,0.014 0.00681,0.02311 0.00781,0.03711c-0.055,2.122 2.04645,3.46592 3.93945,3.79492c2.47,0.43 5.0352,0.53089 7.5332,0.71289c2.545,0.186 5.09162,0.32969 7.64062,0.42969c1.172,0.046 2.3852,0.1448 3.5332,-0.1582c1.112,-0.294 2.08825,-0.95784 3.03125,-1.58984c1.988,-1.332 3.92798,-2.78477 5.70898,-4.38477c0.845,-0.76 1.32945,-1.58166 1.43945,-2.72266c0.121,-1.257 0.16319,-2.52516 0.24219,-3.78516c0.326,-5.166 0.65156,-10.33205 0.97656,-15.49805c0.161,-2.549 0.33847,-5.09648 0.48047,-7.64648c0.128,-2.266 -0.07159,-4.51711 -1.43359,-6.41211c-2.66,-3.698 -7.25234,-4.61348 -11.52734,-4.89648c-3.83175,-0.25275 -7.67695,-0.35844 -11.51953,-0.36523zM22.97656,7.69336c2.351,-0.002 4.70178,0.03433 7.05078,0.11133c2.312,0.075 4.63941,0.14105 6.94141,0.37305c2.024,0.204 4.09509,0.62616 5.87109,1.66016c1.418,0.826 2.7263,2.06448 3.2793,3.64648c0.732,2.097 0.2942,4.52836 0.1582,6.69336c-0.3,4.753 -0.59844,9.50581 -0.89844,14.25781l-0.45312,7.19141c-0.036,0.57 -0.07142,1.14094 -0.10742,1.71094c-1.609,-0.596 -3.23495,-1.14758 -4.87695,-1.64258c-1.382,-0.416 -3.1257,-0.85389 -4.2207,0.41211c-0.543,0.628 -0.78775,1.49602 -0.96875,2.29102c-0.187,0.819 -0.28722,1.66395 -0.32422,2.50195c-0.063,1.434 0.07033,2.86506 0.23633,4.28906c-0.142,0.002 -0.28269,0.00791 -0.42969,0.00391c-1.179,-0.032 -2.35911,-0.08944 -3.53711,-0.14844c-2.384,-0.118 -4.76553,-0.27575 -7.14453,-0.46875c-1.149,-0.094 -2.29636,-0.19469 -3.44336,-0.30469c-0.985,-0.095 -2.05761,-0.12801 -2.97461,-0.54102c-0.744,-0.335 -1.28936,-0.95974 -1.19336,-1.80273c0.012,-0.103 -0.00616,-0.20092 -0.03516,-0.29492c-0.131,-9.294 -1.13067,-18.55366 -1.26367,-27.84766c-0.033,-2.333 -0.0103,-4.66705 0.0957,-6.99805c0.048,-1.055 0.03023,-2.16317 0.24024,-3.20117c0.178,-0.878 0.5758,-1.44487 1.4668,-1.67187c0.943,-0.241 1.97936,-0.1705 2.94336,-0.1875c1.196,-0.021 2.39189,-0.0322 3.58789,-0.0332zM21.69531,13.81055c-0.34413,-0.02075 -0.68866,-0.01973 -1.03516,0.00976c-0.27,0.023 -0.51003,0.09597 -0.70703,0.29297c-0.08,0.08 -0.14722,0.18583 -0.19922,0.29883c-0.047,0.051 -0.11144,0.08744 -0.14844,0.14844c-0.243,0.556 -0.45909,1.11813 -0.62109,1.70313c-0.069,0.25 -0.02944,0.54748 0.10156,0.77148c0.121,0.208 0.35866,0.40398 0.59766,0.45898c0.145,0.033 0.29536,0.03472 0.44336,0.01172c0.09,0.028 0.18234,0.04702 0.27734,0.04102c0.279,-0.018 0.55889,-0.03669 0.83789,-0.05469c0.126,-0.008 0.25291,-0.01239 0.37891,-0.02539c0.215,-0.021 0.3998,-0.0522 0.5918,-0.1582c0.158,-0.087 0.26509,-0.19894 0.37109,-0.33594c0.181,-0.234 0.27748,-0.52983 0.39648,-0.79883c0.113,-0.257 0.22684,-0.51448 0.33984,-0.77148c0.09,-0.156 0.13572,-0.32486 0.13672,-0.50586c0.005,-0.138 -0.02294,-0.26577 -0.08594,-0.38476c-0.108,-0.264 -0.34748,-0.52908 -0.64648,-0.58008c-0.342,-0.0585 -0.68517,-0.10034 -1.0293,-0.12109zM27.30273,15.25391c-1.285,-0.079 -1.281,1.921 0,2c4.32,0.266 8.63813,0.5682 12.95313,0.9082c1.283,0.101 1.277,-1.9 0,-2c-4.315,-0.34 -8.63312,-0.6422 -12.95312,-0.9082zM20.98242,21.92578c-0.17105,0.01358 -0.33986,0.04595 -0.50586,0.0957c-0.091,0.027 -0.17291,0.07891 -0.25391,0.12891c-0.19,0.084 -0.37898,0.2173 -0.45898,0.4043c-0.151,0.35 -0.289,0.69831 -0.375,1.07031c-0.086,0.371 -0.13725,0.75477 -0.15625,1.13477c-0.012,0.238 0.11797,0.54603 0.29297,0.70703c0.067,0.052 0.13417,0.1023 0.20117,0.1543c0.156,0.091 0.32486,0.13672 0.50586,0.13672c0.288,0.029 0.57723,0.05698 0.86523,0.08398c0.213,0.02 0.39337,0.0277 0.60938,-0.0293c0.197,-0.051 0.38244,-0.17441 0.52344,-0.31641c0.161,-0.162 0.22659,-0.3213 0.30859,-0.5293c0.234,-0.594 0.34673,-1.22061 0.42773,-1.84961c-0.001,-0.18 -0.04572,-0.34791 -0.13672,-0.50391c-0.089,-0.15 -0.20938,-0.27038 -0.35937,-0.35937c-0.45225,-0.2505 -0.97514,-0.36886 -1.48828,-0.32812zM27.68555,22.87305c-1.286,-0.069 -1.282,1.931 0,2c3.812,0.206 7.61892,0.48589 11.41992,0.83789c0.539,0.05 1,-0.495 1,-1c0,-0.582 -0.46,-0.95 -1,-1c-3.801,-0.352 -7.60792,-0.63189 -11.41992,-0.83789zM19.98242,28.92578c-0.17105,0.01358 -0.33986,0.04595 -0.50586,0.0957c-0.091,0.027 -0.17291,0.07891 -0.25391,0.12891c-0.19,0.084 -0.37898,0.2173 -0.45898,0.4043c-0.151,0.35 -0.289,0.69831 -0.375,1.07031c-0.086,0.371 -0.13725,0.75477 -0.15625,1.13477c-0.012,0.238 0.11797,0.54603 0.29297,0.70703c0.067,0.052 0.13417,0.1023 0.20117,0.1543c0.156,0.091 0.32486,0.13672 0.50586,0.13672c0.288,0.029 0.57723,0.05698 0.86523,0.08398c0.213,0.02 0.39337,0.0277 0.60938,-0.0293c0.197,-0.051 0.38244,-0.17441 0.52344,-0.31641c0.161,-0.162 0.22659,-0.3213 0.30859,-0.5293c0.234,-0.594 0.34673,-1.22061 0.42773,-1.84961c-0.001,-0.18 -0.04572,-0.34791 -0.13672,-0.50391c-0.089,-0.15 -0.20938,-0.27038 -0.35937,-0.35937c-0.45225,-0.2505 -0.97514,-0.36886 -1.48828,-0.32812zM27.17188,30.03711c-1.287,-0.017 -1.288,1.983 0,2c3.686,0.05 7.36902,0.24031 11.04102,0.57031c0.539,0.049 1,-0.494 1,-1c0,-0.582 -0.46,-0.952 -1,-1c-3.672,-0.331 -7.35402,-0.52131 -11.04102,-0.57031z"></path></g></g></svg>
  </button>
  <div class="containers" :class="{ expanded: mapExpanded }">
    <div class="category">
      <input v-model="categorySearch" placeholder="جستجوی دسته‌بندی..." class="search-box" />
      <div class="category-list">
        <button @click="selectedCategoryId = null">همه</button>
        <button
          v-for="cat in filteredCategories"
          :key="cat.id"
          :class="{ active: selectedCategoryId === cat.id }"
          @click="selectedCategoryId = cat.id">
          {{ cat.title }}
        </button>
      </div>
    </div>
    <div class="info">
      <div v-for="place in filteredPlaces" :key="place.id" class="place-item">
        <h4>
          {{ place.title }}
        </h4>
        <MediaSlider v-if="place.medias?.length>0" :medias="place.medias"/>
        <small class="cat" v-for="category in place.categories" :key="category.id">
          {{ category.title }}
        </small>
        <p>{{ truncateText(place.description) }}</p>
        <small>
          {{ truncateText(place.addresses?.[0]?.address) || '' }}
        </small>
        <a v-if="place.addresses?.[0]?.lat && place.addresses?.[0]?.lon" @click.prevent="flyToPlace(place.id,place.addresses[0].lat,place.addresses[0].lon)">محل دقیق</a>
      </div>
    </div>
  </div>
</template>
<script setup>
  import StaticMapWithMarkers from '@/components/tooles/places/StaticMapWithMarkers.vue'
  import { usePlaceStore } from '@/stores/place'
  import MediaSlider from '@/components/tooles/media/MediaSlider.vue'
  import { onMounted, computed, ref } from 'vue'
  const placeStore = usePlaceStore()
  const mapExpanded = ref(false)
  const center = computed(() => {
    const cord = placeStore.userCoordinate
    if (cord?.lat && cord?.lon) {
      return { lat: parseFloat(cord.lat), lon: 4.56532 }
      // return { lat: parseFloat(cord.lat), lon: parseFloat(cord.lon) }
    }
    return { lat: 35.6892, lon: 51.3890 }
  })
  const activePlaceId = ref(null)
  const mapCenter = ref(center.value)
  const flyToPlace = (id,lat,lon) => {
    const lt = parseFloat(lat)
    const ln = parseFloat(lon)
    if (!isNaN(lt) && !isNaN(ln)) {
      mapExpanded.value = false
      mapCenter.value = { lat: lt, lon: ln }
      activePlaceId.value = id
    } else {
      console.warn('مختصات نامعتبر برای place:')
    }
  }
  onMounted(() => {
    placeStore.fetchAllPlaces()
  })
  const markers = computed(() =>
    filteredPlaces.value.flatMap(place =>
      (place.addresses || []).map(address => ({
        lat: parseFloat(address.lat),
        lon: parseFloat(address.lon),
        address: address.address || '',
        title: place.title || '',
        description: place.description || '',
        medias: place.medias || [],
        categories: place.categories || [],
        placeId: place.id
      }))
    )
  )
  const selectedCategoryId = ref(null)
  const allCategories = computed(() => {
    const set = new Map()
    placeStore.allPlaces.forEach(place => {
      (place.categories || []).forEach(category => {
        if (!set.has(Number(category.id))) {
          set.set(Number(category.id), category)
        }
      })
    })
    return Array.from(set.values())
  })
  const categorySearch = ref('')
  const filteredCategories = computed(() => {
    const q = categorySearch.value.trim().toLowerCase()
    if (!q) return allCategories.value
    return allCategories.value.filter(c => c.title.toLowerCase().includes(q))
  })
  const filteredPlaces = computed(() => {
    if (!selectedCategoryId.value) return placeStore.allPlaces
    return placeStore.allPlaces.filter(place =>
      (place.categories || []).some(cat => Number(cat.id) === Number(selectedCategoryId.value))
    )
  })
  const truncateText = (text, max = 50) => {
    if (!text) return ''
    return text.length > max ? text.slice(0, max) + '...' : text
  }
</script>
<style>
  .map-in{
    height: 75%;
    width: 100%;
    transition: height 0.3s ease;
    opacity: 1;
  }
  .layout-toggle{
    width: 100%;
    height: 4.5%;
    border: none;
    background: #ffdd00;
    outline: none;
  }
  .layout-toggle svg{
    height: 100%;
    width: 100%;
  }
  .containers{
    height: 20%;
    opacity: 0.3;
    display: flex;
    gap: 2%;
    flex-direction: row-reverse;
    align-items: stretch;
    justify-content: center;
    flex-wrap: nowrap;
  }
  .map-in.expanded{
    height: 20%;
    opacity: 0.3;
  }
  .containers.expanded{
    height: 75%;
    opacity: 1;
  }
  .category{
    height: 100%;
    float: right;
    width: 29%;
  }
  .category input{
    height: 30px;
    width: 100%;
    margin: 5px auto;
    box-sizing: border-box;
    border-radius: 5px;
    outline: none;
    border: 0;
    background: #e5dede;
    box-shadow: 0 0 5px;
    direction: rtl;
  }
  .category-list{
    height: calc(100% - 30px);
    width: 100%;
    overflow: auto;
    display: flex;
    flex-direction: column;
    flex-wrap: nowrap;
    align-items: stretch;
    justify-content: flex-start;
    gap: 2px;
  }
  .info{
    height: 100%;
    gap: 10px;
    overflow: auto;
    width: 69%;
    display: flex;
    flex-direction: row-reverse;
    flex-wrap: wrap;
    align-content: flex-start;
    justify-content: center;
    align-items: center;
  }
  .category-list button {
    color: white;
    background: #4a6b75;
    padding: 8px;
    border: none;
    outline: none;
    border-radius: 10px;
    box-shadow: 0 0 5px green;
  }
  .place-item {
    position: sticky;
    top: 0;
    padding: 10px;
    box-sizing: border-box;
    background: #bcebbc;
    border-radius: 10px;
    width: 100%;
    text-align: center;
  }
  h4,p,small{
    margin: 0 0 5px 0;
  }
  .place-item a {
    width: 100%;
    display: inline-block;
    padding: 10px;
    background: yellow;
    border-radius: 10px;
    box-sizing: border-box;
    text-align: center;
  }
  .cat{
    padding: 3px 5px;
    background: lightgrey;
    border-radius: 5px;
    display: inline-block;
    margin: 5px;
  }
</style>