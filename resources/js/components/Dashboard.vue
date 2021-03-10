<template>
  <v-app>
    <v-navigation-drawer
      absolute
      permanent
      floating
      class="sidebar"
    >
      <div class="d-flex flex-column align-center justify-center">
        <img src="/assets/logo.png" alt="Stendard logo" width="120px" class="mt-14">
        <v-btn large class="mt-7 add-button" elevation="2">
          <v-icon color="green">mdi-plus</v-icon>
          <b>Add</b>
        </v-btn>
      </div>
      <v-list class="mt-13">
        <v-list-item
          v-for="(item, i) in items"
          :key="i"
          link
        >
          <div v-if="!item.children" class="d-flex w-full">
            <v-list-item class="pr-8 pl-10 text-bolder">
              <v-list-item-icon class="menu-icon">
                <v-icon>{{ item.icon }}</v-icon>
              </v-list-item-icon>

              <v-list-item-content>
                <v-list-item-title><p class="ma-0">{{ item.text }}</p></v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </div>

          <div v-else class="w-full">
            <v-list-item 
              @click="showChildren" 
              :class="{active:toggleMenu && !toggleChildren}" 
              class="px-4 text-bolder d-flex menu-list"
            >
              <v-list-item-icon class="menu-icon">
                <v-icon>{{ item.icon }}</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title><p class="text-ellipsis ma-0">{{ item.text }}</p></v-list-item-title>
              </v-list-item-content>
              <v-list-item-icon>
                <v-icon>{{ toggleMenu ? "mdi-chevron-up" : "mdi-chevron-down" }}</v-icon>
              </v-list-item-icon>
            </v-list-item>
            <div v-if="toggleMenu">
              <v-list-item 
                v-for="(list,i) in item.children" 
                :key="i"
                class="w-full"
              >
                <div v-if="!list.children || list.children.length == 0" class="d-flex pr-4 pl-12">
                  <v-list-item class="text-bolder-darker">
                    <v-list-item-icon class="menu-icon">
                      <v-icon v-if="list.type == 'itsfolder'">mdi-folder-outline</v-icon>
                      <v-icon v-else>{{ list.icon }}</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                      <v-list-item-title><p class="text-ellipsis ma-0">{{ list.text }}</p></v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </div>
                <div v-else class="w-full">
                  <v-list-item 
                    @click="showGrandChildren(list)" 
                    :class="{active:toggleChildren}"
                    class="text-bolder-darker pl-6 menu-list"
                  >
                    <v-list-item-icon class="menu-icon">
                      <v-icon>mdi-folder-outline</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                      <v-list-item-title><p class="text-ellipsis ma-0">{{ list.text }}</p></v-list-item-title>
                    </v-list-item-content>
                    <v-list-item-icon>
                      <v-icon>{{ toggleChildren ? "mdi-chevron-up" : "mdi-chevron-down" }}</v-icon>
                    </v-list-item-icon>
                  </v-list-item>
                  <div v-if="toggleChildren">
                    <v-list-item 
                      v-for="(child,i) in list.children"
                      :key="i"
                      class="text-bolder-darker pl-16"
                    >
                      <v-list-item-icon class="menu-icon">
                        <v-icon>mdi-folder-outline</v-icon>
                      </v-list-item-icon>
                      <v-list-item-content>
                        <v-list-item-title><p class="text-ellipsis ma-0">{{ child.text }}</p></v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                  </div>
                </div>
              </v-list-item>
            </div>
          </div>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-app-bar app color="#4A85C5" class="navbar">
      <v-autocomplete
        v-model="select"
        :items="searchItem"
        :search-input.sync="search"
        cache-items
        class="mx-4 autocomplete"
        flat
        hide-details
        label="Search solution..."
        clearable
        rounded
        solo-inverted
      ></v-autocomplete>
      <v-spacer></v-spacer>
      <a class="text-white text-sm mr-12">10 Days left for your trial account</a>
      <v-btn small color="#EF8019" class="text-white small-btn mr-12">Upgrade My Plan</v-btn>
      <div class="d-flex align-center justify-center progress-bar mr-12">
        <div class="d-flex flex-column mr-1">
          <span>Used</span>
          <b>{{ formatBytes(usedMemory) }}</b>
        </div>
        <v-progress-linear
          color="#FF8717"
          background-color="white"
          rounded
          :value="usedMemoryPercentage"
        ></v-progress-linear>
        <div class="d-flex flex-column ml-1">
          <span>Total</span>
          <b>{{ totalMemory / 1000000 }}Gb</b>
        </div>
      </div>
      <v-btn
        icon
        class="mr-4"
      >
        <v-icon class="text-white">mdi-office-building-outline</v-icon>
      </v-btn>
      <v-btn
        icon
        class="mr-4"
      >
        <v-icon class="text-white">mdi-bell-ring-outline</v-icon>
      </v-btn>
    </v-app-bar>
    <v-main>
      <Content :datas="datas" />
    </v-main>
  </v-app>
</template>

<script>
import axios from 'axios'
import Content from './Content'

export default {
  name: 'Dashboard',
  components: {
    Content
  },
  data: () => ({
    toggleMenu: true,
    toggleChildren: false,
    searchItem: [],
    search: null,
    selectedFolder: null,
    select: null,
    libraryData: [],
    sizeUsed: [],
    datas: [],
    tempData: [],
    totalMemory: 2000000,
    items: [
      {
        icon: 'mdi-face-outline',
        text: 'Indri Ruth Simatupang'
      },
      {
        icon: 'mdi-layers-outline',
        text: 'Document Generator'
      },
      {
        icon: 'mdi-folder-outline',
        text: 'Library',
        children: [
          {
            icon: 'mdi-folder-outline',
            text: 'Evidence Submission',
          },
          {
            icon: 'mdi-google-drive',
            text: 'Connect My Drive',
          },
          {
            icon: 'mdi-trash-can-outline',
            text: 'Bin',
          }
        ]
      },
      {
        icon: 'mdi-swap-horizontal',
        text: 'Workflow'
      },
      {
        icon: 'mdi-alert-circle-outline',
        text: 'Help'
      },
      {
        icon: 'mdi-format-list-numbered',
        text: 'Audit Trail'
      },
      {
        icon: 'mdi-history',
        text: 'Changelog'
      },
    ],
    sidebarMenu: [],
  }),
  beforeCreate(){
    if ( localStorage.getItem('isLoggedIn') == true ) {
      this.$router.push({name: 'Login'})
    }
  },
  created(){
    axios.get('api/sidebar')
    .then((response) => {
      response.data.map( item => 
        this.sidebarMenu.push(item)
      )
      this.sidebarMenu.map(item => {
        this.items[2].children.push(item)
        this.tempData.push(item)
        this.datas.push(item)
      })
    })
    axios.get('api/library')
    .then(response => {
      response.data.map( item => {
        this.libraryData.push(item)
      })
      this.libraryData.map(item => {
        if ( item.size_total ) {
          this.sizeUsed.push(item.size_total)
        }
      })
    })
  },
  watch: {
    search (val) {
      val && val !== this.select && this.querySelections(val)
    },
  },
  computed: {
    usedMemory() {
      return this.sizeUsed.reduce((sum, value) => {
        return sum + value
      },0)
    },
    usedMemoryPercentage() {
      return this.usedMemory / 2147483648 * 100
    }
  },
  methods: {
    showChildren() {
      this.toggleMenu = !this.toggleMenu
    },
    showGrandChildren(value) {
      this.toggleChildren = !this.toggleChildren
      this.toggleChildren ? this.datas = value.children : this.datas = this.tempData
    },
    querySelections (v) {
      setTimeout(() => {
        this.searchItem = this.sidebarMenu.filter(e => {
          return (e.text || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
        })
      }, 500)
    },
    formatBytes(bytes, decimals = 2) {
      if (bytes === 0) return '0 Bytes';

      const k = 1024;
      const dm = decimals < 0 ? 0 : decimals;
      const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

      const i = Math.floor(Math.log(bytes) / Math.log(k));

      return parseFloat((bytes / Math.pow(k, i)).toFixed(dm))+ sizes[i];
    }
  }
}
</script>

<style lang="scss" scoped>
  .active {
    background-color: #E7F3FF;
    p, i {
      color: #1890FF !important;
    }
  }

  .text-bolder {
    p, i {
      font-weight: 900;
      color: #777777;
    }
  }

  .text-bolder-darker {
    p, i {
      font-weight: 900;
      color: #4a4a4a;
    }
  }

  .fw-900 {
    font-weight: 900;
  }

  .text-white {
    color: white !important;
  }
  header {
    left: 255px !important;
  }

  main {
    margin-left: 255px;
    // height: 100vh;
  }

  .v-list-item__title {
    font-size: 12px;
    line-height: 16px;
  }

  .v-icon, .text-sm {
    font-size: 12px;
  }

  ::v-deep .v-list-item {
    padding: 0;
  }

  .text-ellipsis {
    width: 120px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
  }

  .autocomplete {
    background-color: white;
  }

  .progress-bar {
    width: 200px;
    span {
      font-size: 8px;
    }
    b {
      font-size: 10px;
    }
  }

  .navbar {
    color: white;
  }

  .sidebar {
    background-color: #FAFAFA;
  }

  .small-btn {
    font-size: 10px;
    text-transform: none;
    font-weight: bold;
  }

  #list-28 {
    padding: 0 12px;
    width: 314px;
  }

  .add-button {
    text-transform: none;
    background-color: white !important;
    min-width: 100px !important;
    height: 40px !important;
    border-radius: 12px;
    b {
      color: #1890FF;
    }
  }

  .menu-list {
    .v-list-item__icon {
      order: 1;
      margin-left: 0 !important;
    }
    .v-list-item__content {
      order: 3;
    }
    .menu-icon {
      order: 2;
    }
    .v-list-item__icon {
      margin-top: 18px;
    }
  }

  .menu-icon {
    margin-right: 0 !important;
  }
</style>
