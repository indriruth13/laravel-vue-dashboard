<template>
  <v-container class="py-10 pl-10 content-container">
    <v-navigation-drawer
      v-model="drawer"
      :mini-variant.sync="mini"
      floating
      permanent
      absolute
      right
      :class="{'right-sidebar':!mini,'sidebar-width':mini}"
    >
      <v-list-item class="pt-12">
        <v-btn
          icon
          @click.stop="mini = !mini"
          :class="{'show-sidebar':mini}"
        >
          <v-icon>{{ mini ? 'mdi-chevron-left' : 'mdi-chevron-right'}}</v-icon>
        </v-btn>
        <v-list v-model="mini" class="top-option d-flex">
          <v-btn small depressed class="edit-btn">Live Edit</v-btn>
          <v-btn small depressed>
            <v-icon class="text-sm">mdi-download-outline</v-icon>
          </v-btn>
          <v-btn small depressed>
            <v-icon class="text-sm">mdi-link</v-icon>
          </v-btn>
          <v-btn small depressed>
            <v-icon class="text-sm">mdi-trash-can-outline</v-icon>
          </v-btn>
        </v-list>
      </v-list-item>
      <v-list v-model="mini" class="px-8 pt-12">
        <v-list-item two-line>
          <v-list-item-content class="detail-data">
            <div v-if="detailedData.type !== 'itsfolder'">
              <v-list-item-subtitle><b>Document Name</b></v-list-item-subtitle>
              <v-list-item-title>{{ detailedData.filename ? detailedData.filename : '-' }}</v-list-item-title>
              <v-list-item-subtitle><b>Visibility</b></v-list-item-subtitle>
              <v-list-item-title>Anyone With Link</v-list-item-title>
              <v-list-item-subtitle><b>Owner</b></v-list-item-subtitle>
              <v-list-item-title>Indri Ruth Simatupang</v-list-item-title>
              <v-list-item-subtitle><b>Format</b></v-list-item-subtitle>
              <v-list-item-title>{{ detailedData.file_type ? detailedData.file_type : '-' }}</v-list-item-title>
              <v-list-item-subtitle><b>Version</b></v-list-item-subtitle>
              <v-list-item-title>-</v-list-item-title>
              <v-list-item-subtitle><b>Size</b></v-list-item-subtitle>
              <v-list-item-title>{{ detailedData.size ? detailedData.size : '-' }}</v-list-item-title>
              <v-list-item-subtitle><b>Upload</b></v-list-item-subtitle>
              <v-list-item-title>-</v-list-item-title>
              <v-list-item-subtitle><b>Status</b></v-list-item-subtitle>
              <v-list-item-title>{{ detailedData.file_status ? detailedData.file_status : '-' }}</v-list-item-title>
              <v-list-item-subtitle><b>Status</b></v-list-item-subtitle>
              <v-list-item-title>{{ detailedData.file_last_updated ? detailedData.file_last_updated : '-' }}</v-list-item-title>
              <v-list-item-subtitle><b>Location</b></v-list-item-subtitle>
              <v-list-item-title>Library</v-list-item-title>
              <v-card elevation="2" class="mt-12">
                <div class="card-container">
                  <div class="card-title"><b>Who Can Edit</b></div>
                  <div class="d-flex flex-column pb-5 pl-2">
                    <div 
                      v-for="(item,i) in editor"
                      :key="i"
                      class="pt-4 pb-2 px-2 d-flex justify-space-between editor"
                      :class="{show: i == hoveredItem, hidden: i !== hoveredItem}"
                      @mouseover="showDeleteButton(i)"
                      @mouseleave="showDeleteButton(i)"
                    >
                      <div><span>{{ item }}</span></div>
                      <div @click="removeEditor(i)" class="icon-container">
                        <v-icon 
                        >mdi-close</v-icon>
                      </div>
                    </div>
                    <div class="pt-4 pb-2 px-2 add-btn" @click="addEditor">Add Editor</div>
                  </div>
                </div>
              </v-card>
            </div>
            <div v-else>
              <v-list-item-subtitle><b>Folder Name</b></v-list-item-subtitle>
              <v-list-item-title class="text-ellipsis text-ellipsis-long">{{ detailedData.text ? detailedData.text : '-' }}</v-list-item-title>
              <v-list-item-subtitle><b>Visibility</b></v-list-item-subtitle>
              <v-list-item-title>Anyone With Link</v-list-item-title>
              <v-list-item-subtitle><b>Owner</b></v-list-item-subtitle>
              <v-list-item-title>Indri Ruth Simatupang</v-list-item-title>
            </div>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-container class="filter-container">
      <v-btn class="filter-button mb-4" elevation="0" @click="showFilterOptions">
        <v-icon class="mr-2">mdi-filter-variant</v-icon>
        <b>Filter</b>
      </v-btn>
      <div v-show="showFilter">
        <v-row>
          <div 
            class="d-flex flex-column col-3"
            v-for="(item,i) in options"
            :key="i"
          >
            <label>{{ item.label }}</label>
            <v-select 
              solo 
              :placeholder="item.placeholder"
              :items="item.options"
              v-model="selectedItem"
              @input="selectedOption(selectedItem, item.value)"
            ></v-select>
          </div>
          <div class="col-3 d-flex align-center ml-5 option-button">
            <v-btn elevation="0" class="mr-2" @click="showFilter = !showFilter">
              <v-icon>mdi-close</v-icon>
              <b>Close</b>
            </v-btn>
            <v-btn elevation="0" @click="search.type = null">
              <v-icon>mdi-restore</v-icon>
              <b>Reset Filter</b>
            </v-btn>
          </div>
        </v-row>
      </div>
    </v-container>
    <div class="alert-container" v-show="showAlert">
      <v-card 
        class="d-flex justify-space-between align-center alert-card" 
        elevation="5"
      >
        <div class="d-flex flex-column">
          <b>Succesfully move the file to</b>
          <div class="d-flex align-center mt-3">
            <img src="/assets/opened-folder-icon.png" class="mr-2" alt="Folder Icon">
            <span class="text-ellipsis">{{ dropTo }}</span>
          </div>
        </div>
        <div @click="showAlert = !showAlert">
          <v-icon>mdi-close</v-icon>
        </div>
      </v-card>
    </div>
    <div class="mb-12">
      <h4 class="mb-3">Recent Files</h4>
      <div>
        <div class="d-flex flex-wrap">
          <drag
            v-for="data in fileData"
            :key="data.id"
            class="mr-6 pa-2"
            :class="{'file-active': data.id == activeItem}"
            :transfer-data="data"
            :effect-allowed="['move']"
				    drop-effect="move"
          >
            <template slot="image">
              <div class="file-active drag-image px-3 py-2 d-flex align-center">
                <img src="/assets/google-docs-icon.png" alt="Google Docs Icon">
                <b class="ml-1">{{ data.file }}</b>
              </div>
            </template>
            <div class="item-content flex-1" @click="showDetail(data)">
              <img src="/assets/google-docs-icon.png" alt="Google Docs Icon" v-if="data.file_type =='docx'">
              <img src="/assets/google-slides-icon.png" alt="Google Slides Icon" v-else>
              <div class="pa-1 text-center">
                <div><p class="text-ellipsis text-bold mb-0">{{ data.file }}</p></div>
              </div>
            </div>
          </drag>
        </div>
      </div>
    </div>
    <div>
      <h4 class="mb-3">Files</h4>
      <div>
        <div class="d-flex flex-wrap">
          <drop
            v-for="(data,i) in datas"
            :key="i"
            class="mr-6 pa-2 drop"
            :class="{'file-active': data.id == activeItem, drag: over}"
            @drop="handleDrop(data)"
            @dragover="over = true"
            @dragleave="over = false"
          >
            <div class="flex-1" @click="showDetail(data)">
              <img src="/assets/opened-folder-icon.png" alt="">
              <div class="pa-1 text-center">
                <div><p class="text-ellipsis text-bold mb-0">{{ data.text }}</p></div>
              </div>
            </div>
          </drop>
        </div>
      </div>
    </div>
    <v-data-table
      :headers="headers"
      :items="filteredData"
      class="mt-12"
      :hide-default-footer="true"
      :custom-sort="customSort"
    >
      <template v-slot:item="props">
        <tr @contextmenu="show">
          <td> 
            {{ props.item.file }} 
          </td>
          <td> {{ props.item.doc_no }} </td>
          <td> {{ props.item.size }} </td>
          <td> {{ props.item.file_status }} </td>
          <td> {{ props.item.last_updated }} </td>
        </tr>
      </template>
    </v-data-table>
    <v-menu
        v-model="showMenu"
        :position-x="x"
        :position-y="y"
        absolute
        offset-y
      >
        <v-list>
          <v-list-item
            v-for="(item, index) in contextMenuOptions"
            :key="index"
            @click="item.menuAction(item.title)"
            class="context-menu pa-0"
            :class="{'border-bottom': item.borderBottom,'border-top': item.borderTop}"
          >
            <div class="d-flex align-center px-4">
              <v-icon class="mr-3">{{ item.icon }}</v-icon>
              <v-list-item-title>
                  <span>{{ item.title}}</span>
              </v-list-item-title>
            </div>
          </v-list-item>
        </v-list>
      </v-menu>
  </v-container>
</template>

<script>
import axios from 'axios'
import { Drag, Drop } from 'vue-drag-drop';

export default {
  name: 'Content',
  props: ['datas', 'breadcrumbs'],
  data: () => ({
    over: false,
    drawer: true,
    mini: true,
    activeItem: null,
    hover: false,
    showAlert: false,
    hoveredItem: null,
    dropTo: null,
    fileData: [],
    detailedData: {},
    showMenu: false,
    showFilter: false,
    search: {
      type: null,
      value: ''
    },
    selectedItem: null,
    x: 0,
    y: 0,
    headers: [
      { text: 'Name', value: 'file'},
      { text: 'Doc No', value: 'doc_no' },
      { text: 'Size', value: 'size' },
      { text: 'Status', value: 'file_status' },
      { text: 'Last Updated', value: 'last_updated' }
    ],
    editor: ['Ahyar Afal Imanudin', 'Aisyah Marlinda Saputri'],
    contextMenuOptions: [
      { title: 'Preview', icon: 'mdi-eye-outline', menuAction: action => { alert(action) } },
      { title: 'Live Edit', icon: 'mdi-square-edit-outline',menuAction: action => { alert(action) } },
      { title: 'Edit in Word 365', icon: 'mdi-file-outline',menuAction: action => { alert(action) } },
      { title: 'Rename', icon: 'mdi-format-italic',menuAction: action => { alert(action) } },
      { title: 'Copy Link', icon: 'mdi-link',menuAction: action => { alert(action) } },
      { title: 'Move File', icon: 'mdi-cursor-move',menuAction: action => { alert(action) } },
      { title: 'Start Version Control', icon: 'mdi-format-list-numbered',menuAction: action => { alert(action) } },
      { title: 'See Workflow', icon: 'mdi-swap-horizontal',menuAction: action => { alert(action) } },
      { title: 'Share', icon: 'mdi-share-variant-outline',menuAction: action => { alert(action) } },
      { title: 'Document Info', icon: 'mdi-information-outline',menuAction: action => { alert(action) }, borderTop: true },
      { title: 'Download', icon: 'mdi-download-outline',menuAction: action => { alert(action) }, borderBottom: true },
      { title: 'Delete File', icon: 'mdi-trash-can-outline',menuAction: action => { alert(action) } },
    ],
    options: [
      { label: 'Document Type', placeholder: 'Manual, procedure,etc', options: ['Folder', 'File'], value: null},
      { label: 'Format', placeholder: 'pdf,doc or xls', options: ['docx', 'jpg'], value: 'file_type'},
      { label: 'Author', placeholder: '-', options: ['Ahmad', 'Budi'], value: null},
      { label: 'Department', placeholder: 'Select department', options: ['Tech', 'Marketing'], value: null},
      { label: 'Category', placeholder: 'Select category', options: ['Category 1', 'Category 2'], value: 'type'},
      { label: 'File Status', placeholder: 'Draft, published, etc', options: ['Draft', 'Published'], value: 'file_status'}
    ]
  }),  
  created() {
    axios.get('api/library')
    .then(response => {
      response.data.map( item => {
        item.filename ? this.fileData.push(item) : null
      })
    })
  },
  computed: {
    filteredData() {
      if ( this.search.type === null ) {
        return this.fileData 
      } else {
        return this.fileData.filter((i) => {
          return i[this.search.type] === this.search.value
        })
      }
    }
  },
  methods: {
    showFilterOptions() {
      this.showFilter = !this.showFilter
      if (!this.mini) this.mini = !this.mini
    },
    selectedOption(item,value){
      this.search.type = value
      this.search.value = item
    },
    show (e) {
      e.preventDefault()
      this.showMenu = false
      this.x = e.clientX
      this.y = e.clientY
      this.$nextTick(() => {
        this.showMenu = true
      })
    },
    handleDrop(to, data) {
      this.over = false;
      this.dropTo = to.text
      this.showAlert = !this.showAlert
    },
    showDetail(value) {
      this.detailedData = value
      this.mini = false
      this.drawer = false
      this.activeItem = value.id
    },
    showDeleteButton(index) {
      this.hover = !this.hover
      this.hover ? this.hoveredItem = index : this.hoveredItem = null
    },
    addEditor(){
      this.editor.push('New Editor')
    },
    removeEditor(index){
      this.editor.splice(index, 1);
    },
    customSort(items, index, isDesc) {
      items.sort((a, b) => {
          if (index[0]=='last_updated') {
            if (!isDesc[0]) {
                return new Date(b[index]) - new Date(a[index]);
            } else {
                return new Date(a[index]) - new Date(b[index]);
            }
          }
          else {
            if(typeof a[index] !== 'undefined'){
              if (!isDesc[0]) {
                 return a[index].toLowerCase().localeCompare(b[index].toLowerCase());
              }
              else {
                  return b[index].toLowerCase().localeCompare(a[index].toLowerCase());
              }
            }
          }
      });
      return items;
    }
  }
}
</script>

<style lang="scss">
  h4 {
    color: #4a4a4a;
  }

  td {
    border-bottom: none !important;
  }
  .content-container {
    padding-right: 300px !important;
  }

  .text-ellipsis {
    font-size: 12px;
    width: 80px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
  }

  .text-ellipsis-long {
    width: 180px;
  }

  .text-bold {
    font-family: 'Airbnb Cereal Book';
    font-weight: 900;
  }

  .right-sidebar {
    display: none;
    background-color: #FAFAFA !important;
  }

  .sidebar-width {
    width: 40px !important;
  }

  .edit-btn {
    text-transform: none;
    color: #07900C !important ;
    font-weight: 900;
    border-radius: 10px;
  }

  .text-sm {
    font-size: 14px !important;
  }

  .top-option {
    button:not(:first-child) {
      max-width: 35px;
      min-width: 35px !important;
      margin-left: 5px;
    }
  }

  .detail-data {
    .v-list-item__subtitle {
      color: #bbbbbb;
      font-size: 12px;
    }
    .v-list-item__title {
      color: #4a4a4a;
      font-size: 14px;
      font-weight: 900;
      font-family: 'Airbnb Cereal Book';
      margin-top: 6px;
      margin-bottom: 24px !important;
    }
  }

  .v-list-item--active {
    background: #E7F3FF;
    border-radius: 8px;
  }

  .item-content img {
    flex: none;
  }

  .file-active {
    background: #E7F3FF;
    border-radius: 8px;
  }

  .card-container {
    border-radius: 8px;
    font-size: 12px;
    .card-title {
      background: #E7F3FF;
      padding: 16px;
      color: #4A4A4A;
    }
    span, i {
      color: #777777 !important;
    }
    i {
      font-size: 12px !important;
      cursor: pointer;
    }
    .icon-container {
      width: 30px;
      height: 16px;
    }
    .add-btn {
      color: #1890FF;
      cursor: pointer;
    }
  }

  .hidden i {
    display: none !important;
  }

  .show i {
    display: block;
  }

  .alert-container {
    position: fixed;
    top: 15%;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 999;
    .alert-card {
      width: 400px;
      padding: 15px 24px;
      position: relative;
      left: 45%;
      transition: all 0.3s;
      border-radius: 8px;
      background-color: #FAFAFA !important;
      i {
        font-size: 12px !important;
      }
      b {
        font-size: 14px;
      }
      img {
        width: 20px;
        height: 20px;
      }
      span {
        width: 250px;
        font-weight: 900;
        color: #4a4a4a;
      }
    }
  }

  .context-menu {
    min-height: 30px !important;
    i, span {
      font-size: 12px !important;
    }
    .v-list-item__title {
      line-height: 1 !important;
    }
    &:nth-child(2) {
      i, span {
        color: #07900C;
      }
    }
    &:last-child {
      i, span {
        color: #E70000;
;
      }
    }
  }
  .border-bottom {
    border-bottom: 0.5px solid #eeeeee;
  }
  .border-top {
    border-top: 0.5px solid #eeeeee;
  }

  .v-menu__content {
    .v-list-item__content {
      padding-left: 8px;
      padding-right: 8px;
    }
    .v-list-item__title {
      font-size: 12px;
    }
  }

  .filter-container {
    .v-select__selections {
      font-size: 10px;
    }
    .filter-button {
      color: #1890FF;
      b {
        font-size: 14px;
      }
    }
    label, input {
      font-size: 10px;
      font-weight: 900;
    }
    label {
      margin-bottom: 8px;
    }
    button {
      text-transform: none;
    }
    .option-button {
      button,i {
        font-size: 10px !important;
      }
      i {
        margin-right: 6px;
      }
      button:first-of-type{
        background: #1890FF;
        color: white;
      }
      button:last-of-type {
        background-color: #FAFAFA !important; 
        color: #1890FF;
      }
    }
  }

  .show-sidebar {
    width: 100% !important;
    background-color: #FAFAFA;
    height: 60px !important;
    border-radius: 8px 0 0 8px;
  }

  .drag-image {
    b {
      font-size: 12px;
    }
    img {
      width: 50px;
      height: 50px;
    }
  }
</style>