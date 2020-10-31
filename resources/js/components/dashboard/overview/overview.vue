<template>
	<v-main>
		<v-container fluid>
			<v-overlay :value="showFullLoading" :absolute="absolute">
				<v-progress-circular indeterminate size="64"></v-progress-circular>
			</v-overlay>
			<v-row justify="center">
				<v-col cols="12" lg="3" md="3">
                    <DashboardCard :icon="'assignment'" :title="'New Users Today'" :data="dashboard.newAdded" :action="''"  :color="'purple  darken-2'"></DashboardCard>
                </v-col>
				<v-col cols="12" lg="3" md="3">
                    <DashboardCard :icon="'assignment'" :title="'Total Users'" :data="dashboard.totaluser" :action="''" :color="'purple  darken-1'" ></DashboardCard>
                </v-col>
				<v-col cols="12" lg="3" md="3">
                    <DashboardCard :icon="'assignment'" :title="'New Category Today'" :data="dashboard.todaycategory" :action="''"  :color="'purple   darken-3'"></DashboardCard>
                </v-col>
				<v-col cols="12" lg="3" md="3">
                    <DashboardCard :icon="'assignment'" :title="'Total Category'" :data="dashboard.totalcategory" :action="''"  :color="'purple  darken-4'"></DashboardCard>
                </v-col>
                <v-col cols="12" lg="6">
					<p class="title">
						Recent Users
					</p>
                    <v-data-table :items-per-page="itemperpage" :headers="headers" :items="dataUser" :search="search" class="elevation-4">
						<template v-slot:no-data>
							<NoDataList :loading="loading" @initialize="initialize"></NoDataList>
						</template>
					</v-data-table>
                </v-col> 
         
			</v-row>
		</v-container>
		<v-snackbar
			v-model="snackbar"
			:vertical="snackvertical"
			:timeout="snacktimeout"
			:top="snacktop"
			:right="snackright"
			:color="snackcolor"
		>
			{{ snacktext }}
			<v-btn color="white" text @click="snackbar = false">Close</v-btn>
		</v-snackbar>
	</v-main>
</template>

<script>
import NoDataList from './../../common/NoDataList'
import DashboardCard from './../../common/DashboardCard'

export default {
	data: () => ({
		itemperpage:5,
		dashboard:{},
		contact:null,
		search:'',
		image:'/no_image.png',
		isCustomer:false,
		cart:[],
		setting:{},
		valid:false,
		absolute:false,
		branch:'',
		dataBranch:[],
		dataCustomer:[],
		dataProducts:[],
		dataStatus:[],
		loading: false,
		saleweek: [],
		orderweek: [],
		dataUser: [],
		userType: ["Admin", "Staff", "Customer"],
		headers: [
			{ text: "ID", value: "id" },
			{ text: "Name", value: "name" },
			{ text: "Email", value: "email" },
			{ text: "Mobile", value: "mobile" },
			
		],
		nameRules: [
			v => !!v || 'This Field is required'
			],

	}),

	props: {
		source: String
    },
    components:{
		DashboardCard,
		NoDataList
    },
	computed: {

		
	},
	watch: {},
	created() {

	this.initialize();
	},
	methods: {
		handleGoToMenu(d) {
		return d;
		},
		async initialize() {
			this.start()
			this.loading=true;
            this.setting=this.$store.state.setting
            try {
				let { data } = await axios({
					method: "get",
					url: "/app/dashboard"
				});
				this.dashboard = data;
				
			} catch (e) {
				this.fail();
				this.loading=false;
			}
            try {
				let { data } = await axios({
					method: "get",
					url: "/app/user"
				});
				this.dataUser = data;
			} catch (e) {
				this.loading=false;
				this.fail();
			}
			this.loading=false;
			this.finish();

		},
		back()
		{
			this.$router.push('/dashboard/order/list');
		},
		close() {
			this.$refs.form.reset();
			this.cart = [];
			this.loading = false;
		},
		remove(item)
		{
			var index = this.cart.indexOf(item);
			this.cart.splice(index, 1);
		},
	},
	mounted()
	{

	}
};
</script>