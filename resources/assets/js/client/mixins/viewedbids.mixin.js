const viewedStorage = {
	data() {
		return {
			viewedBidsArray: null,
			haslocalStorage: !!localStorage
		};
	},
	mounted() {
		if (localStorage) {
			this.viewedBidsArray = this.getViewedBidsArray();
		}
	},
	methods: {
		getViewedBidsArray() {
			if (!localStorage.viewedBids) {
				localStorage.setItem(
					"viewedBids",
					JSON.stringify(this.viewedBidsArray)
				);
			}
			let arr = localStorage.getItem("viewedBids");
			return JSON.parse(arr) || [];
		},
		findBid(bidId) {
			return this.viewedBidsArray.find(i => i == bidId) != undefined;
		},
		setBidToArray(bidId) {
			if (!this.findBid(bidId)) {
				this.viewedBidsArray.push(bidId);
				localStorage.setItem(
					"viewedBids",
					JSON.stringify(this.viewedBidsArray)
				);
			}
		}
	}
};
export default viewedStorage;