<template>
   <v-app id="inspire">
    <v-snackbar
      v-model="snackbar"
    >
      {{ text }}

      <template v-slot:action="{ attrs }">
        <v-btn
          color="primary"
          text
          v-bind="attrs"
          @click="snackbar = false"
        >
          Close
        </v-btn>
      </template>
    </v-snackbar>
      <v-navigation-drawer
        v-model="drawer"
        :clipped="$vuetify.breakpoint.lgAndUp"
        app
      >
        <v-list dense>
           <v-list-item
              link to="/admin"
            >
              <v-list-item-action>
                <v-icon>mdi-account-group</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>
                  Users
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
           <v-list-item
              link to="/roles"
            >
              <v-list-item-action>
                <v-icon>mdi-account-badge-horizontal</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>
                  Roles
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>

              <v-list-item
              link @click="logout"
            >
              <v-list-item-action>
                <v-icon>mdi-settings</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>
                  Logout
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
             <v-list-item >
              <v-list-item-content>
                <v-list-item-title>
                  <v-switch
                  class="ma-4"
                  label="Theme"
                  @click="change_theme"
                  v-model="Theme"
                  :v-bind="Theme"
                  ></v-switch>
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
        </v-list>
      </v-navigation-drawer>
  
      <v-app-bar
        :clipped-left="$vuetify.breakpoint.lgAndUp"
        app
        color="blue darken-3"
        dark
      >
        <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
        <v-toolbar-title
          style="width: 300px"
          class="ml-0 pl-4"
        >
          <span class="hidden-sm-and-down">Admin Panel</span>
        </v-toolbar-title>
      </v-app-bar>
      <v-main>
<template>
  <v-card>
    <v-card-title>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>
    </v-card-title>
  <v-data-table
    :headers="headers"
    :items="data"
    :search="search"
    class="elevation-1"
    :loading="loading"
    loading-text="Loading... Please wait"
    show-select
    @input="SelectAll"
  >
    <template v-slot:top>
      <v-toolbar
        flat
      >
        <v-toolbar-title>Roles</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <v-dialog
          v-model="dialog"
          max-width="500px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="primary"
              dark
              class="mb-2"
              v-bind="attrs"
              v-on="on"
            >
              New Role 
            </v-btn>
              <v-btn
              color="primary"
              dark
              class="mb-2 mr-2"
              @click="DeleteAll"
            >
              Delete All
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">Edit Role</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col
                  >
                    <v-text-field
                      v-model="editedItem.name"
                      label="Role name"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                color="blue darken-1"
                text
                @click="close"
              >
                Cancel
              </v-btn>
              <v-btn
                color="blue darken-1"
                text
                @click="save"
              >
                Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="headline">Are you sure you want to delete this Role?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editItem(item)"
      >
        mdi-pencil
      </v-icon>
      <v-icon
        small
        @click="deleteItem(item)"
      >
        mdi-delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <v-btn
        color="primary"
        @click="initialize"
      >
        Reset
      </v-btn>
    </template>
  </v-data-table>
</v-card>
</template>
      </v-main>
    </v-app>
</template>

<script>
  export default {
    data: () => ({
    snackbar:false,
     text:null,
     selected:[],
     Theme:localStorage.getItem('theme') || false,
    dialog: false,
    drawer: null,
    data:[],
     search: '',
      dialog: false,
      dialogDelete: false,
      loading:false,
      headers: [
        {
          text: '#',
          align: 'start',
          value: 'id',
        },
        { text: 'Name', value: 'name' },
        { text: 'Created At', value: 'created_at' },
        { text: 'Updated At', value: 'updated_at' },
  
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      data: [],
      editedIndex: -1,
      editedItem: {
        id:'',
        name: '',
      created_at:'',
      updated_at:'',
      },
      defaultItem: {
         id:'',
        name: '',
      created_at:'',
      updated_at:'',
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
      dialogDelete (val) {
        val || this.closeDelete()
      },
    },
mounted(){
localStorage.setItem('theme',false);
},
    created () {
      this.initialize()
      this.Theme = localStorage.getItem('theme');
      this.$vuetify.theme.dark = this.Theme;
    },
    methods: {
      SelectAll:function(e)
      {
      this.selected = [];
      if(e.length > 0)
      {
        this.selected = e.map(val => val.id);
      }
      },
      DeleteAll:function()
      {
          let decide = confirm('Are you sure you want to delete these role`s?')
          if(decide){
         axios.post('/api/roles/delete/', {
           'roles':this.selected,
         })
            .then(res => {
                this.selected.map(val=>{
                  const index = this.data.indexOf(val);
                  this.data.splice(index, 1)
                 this.text = "Record`s Deleted Successfully!";
                })
             this.snackbar = true
            }).catch(err => {
              console.log(err.response)
              this.text = "Error Deleting Record"
              this.snackbar=true
            })
        .catch(err => console.warn(err))
        }
      },
       change_theme:function()
       {
         localStorage.setItem('theme',this.Theme);
          this.$vuetify.theme.dark = this.Theme
       },
           logout:function()
       {
        localStorage.removeItem('token');
        localStorage.removeItem('theme');
          //routing to login page
              this.$router.push('/login').then(res => {
                  this.text = 'logged out!';
                  this.snackbar = true;
                }).catch(
                err => console.warn(err)
              );
            
       },
      initialize () {
              axios.interceptors.request.use((config)=> {
               this.loading = true;
               return config;
              },(error)=> {
                  this.loading = false;
                return Promise.reject(error);
              });

              axios.interceptors.response.use((response)=> {
                 this.loading = false;
               return response;
              },(error)=> {
                  this.loading = false;
                return Promise.reject(error);
              });
               //fecthing roles data
    axios.get('/api/roles/',{})
    .then(res =>{
 this.data = res.data
 })
    .catch(err => {
      if(err.response.status == 401)
      {
        localStorage.removeItem('token');
        this.$router.push('/login');
      }
    })
      },
      editItem (item) {
        this.editedIndex = this.data.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },
 

      deleteItem (item) {
          let decide = confirm('Are you sure you want to delete this role?')
                 const index = this.data.indexOf(item);
                  if(decide){
         axios.delete('/api/roles/'+item.id)
            .then(res => {
                this.text = "Record Deleted Successfully!";
                this.snackbar = true
                this.data =res.data.roles;
            }).catch(err => {
              console.log(err.response)
              this.text = "Error Deleting Record"
              this.snackbar=true
            })
        .catch(err => console.warn(err))
                  }
      },

      deleteItemConfirm (item) {
        this.closeDelete()
      },

      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      save () {
        if (this.editedIndex > -1) {
               const index = this.editedIndex
          //edit req
          axios.put('/api/roles/'+this.editedItem.id,{'name':this.editedItem.name})
         .then(res =>{
            this.text = "Record Updated Successfully!";
            this.snackbar = true;
            this.data = res.data.roles
         })
        .catch(err => console.warn(err))
          //Object.assign(this.data[this.editedIndex], this.editedItem)
        } else {
           //save req
        axios.post('/api/roles',{'name' : this.editedItem.name})
        .then(res => this.data = res.data.roles)
        .catch(err => console.warn(err.response))
        }
        this.close()
      },
    },
  }
</script>