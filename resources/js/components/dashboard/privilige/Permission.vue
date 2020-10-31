<template>
	<v-main>
		<v-container fluid>
            	<v-overlay :value="showFullLoading" :absolute="absolute">
				<v-progress-circular indeterminate size="64"></v-progress-circular>
			</v-overlay>
			<ToolbarLeft>
			</ToolbarLeft>
            	<Breadcrumbs/>
			<v-row justify="center">
				<v-col sm="12" md="12" lg="12" >
					 	<v-card>
						
								<v-card-title>
							<span class="headline">Priviliges And Permission</span>
								</v-card-title>

								<v-card-text>
									<v-container grid-list-md>
										<v-simple-table>
                    <template v-slot:default>
                    <thead>
                        <tr>
                        <th class="text-left">Name</th>
                        <th class="text-left">Check</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td>Pages</td>
                         <td> <v-checkbox v-model="editedItem.pages" ></v-checkbox></td>
                        </tr>
                        <tr>
                        <td>setting</td>
                        <td> <v-checkbox v-model="editedItem.setting" ></v-checkbox></td>
                   
                        </tr>
                    </tbody>
                    </template>
            </v-simple-table>
									</v-container>
								</v-card-text>

								<v-card-actions>
									<v-spacer></v-spacer>
								
									<v-btn
										color="primary"
										:loading="loading"
										:disabled="loading"
										text
										@click="save"
									>Save</v-btn>
								</v-card-actions>
							</v-card>
				</v-col>
			</v-row>
				
		</v-container>
			<v-snackbar
			v-model="snackbar"
			:vertical="snackvertical"
			:timeout="snacktimeout"
			:top="snacktop"
			:color="snackcolor"
			>
			{{ snacktext }}
			<v-btn
				color="white"
				text
				@click="snackbar = false"
			>
				Close
      		</v-btn>
    	</v-snackbar>
	
	
	</v-main>
</template>

<script>
import Breadcrumbs from "./../../common/Breadcrumbs"
import ToolbarLeft from "./../../common/ToolbarLeft"
export default {
	components:{
		Breadcrumbs,
		ToolbarLeft,
	},
data: () => ({
   
   absolute:true,
		loading:false,
        snackbar: false,
        dataIndex: null,
		deleteTitle: "",
		deleteBody: "",
		isDelete: false,
		edit: true,
		dialog: false,
    mode: "",
    timeout: 6000,
    text: "Hello, I'm a snackbar",
    dataUser: {},
    dataType:{},
    editedItem:
    {
      pages: 0,
      setting:0,
      user_id:0,
    }
      
   
  }),
  props: {
    source: String
  },
  computed: {
   
  },
  watch: {},
  created() {
    this.initialize();
  },
  methods: {
    async initialize() {
      try {
        let { data } = await axios({
          method: "get",
          url: "/app/privilige/"+this.$route.params.id
        });
        this.editedItem = data;
      } catch (e) {}

        try {
        let { data } = await axios({
          method: "get",
          url: "/app/getusertype/"+this.$route.params.id
        });
        this.dataType = data;
      } catch (e) {
      }

    },
    
   
    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },
    async save()
    {
        try 
        {
          this.editedItem.user_id=this.$route.params.id
            this.loading=true
            let { data } = await axios({
            method: "post",
            url: "/app/privilige",
            data: this.editedItem
          });
            if (data.status) 
            {
            this.snacks('Successfully Done','green')
            this.loading=false
           
            }
          else
          {
						this.snacks("Failed", "red");
						this.loading = false;
					}
        } catch (e) {
        }
    },
   
  }
};
</script>