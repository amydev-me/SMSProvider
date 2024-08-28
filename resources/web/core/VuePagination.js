module.exports = {
	template:`<nav aria-label="Page navigation example">
				<ul class="pagination justify-content-center" v-show="length>1">
					<li class="page-item">
						<a href="#!" v-bind:class="value===1? 'page-link pagination__navigation--disabled':'page-link'" v-on:click.prevent="$emit('input', value - 1)">	 <i class="fa fa-angle-left"></i></span></a>
					</li>
					
					<li v-for="n in items" :class=" n === value ?'page-item active':'page-item'"> 
						<a href="#!" v-if="!isNaN(n)" v-on:click.prevent="$emit('input', n)" v-text="n" class="page-link""></a>
						<span v-if="isNaN(n)" v-text="n" class="pagination__more"></span>
					</li>

					<li>
						<a href="#!" 
							v-bind:class="value===length? 'page-link pagination__navigation--disabled':'page-link'" 
						v-on:click.prevent="$emit('input', value + 1)"><span class="fa fa-angle-right"></span></a>
					</li>
				</ul>
			</nav>`,

	props: {
		length: {
			type: Number,
			default: 0
		},

		value: {
			type: Number,
			default: 0
		}
	},

	watch: {
		value () {
			this.init();
		}
	},

	computed: {
		items () {
			if (this.length <= 10) {
				return this.range(1, this.length)
			}

			let min = this.value - 3;
			min = min > 0 ? min : 1;

			let max = min + 11;
			max = max <= this.length ? max : this.length;

			if (max === this.length) {
				min = this.length - 11;
			}

			const range = this.range(min, max);

			if (this.value >= 4 && this.length > 6) {
				range.splice(0, 2, 1, '...');
			}

			if (this.value + 3 < this.length && this.length > 6) {
				range.splice(range.length - 2, 2, '...', this.length);
			}

			return range;
		}
	},

	methods: {
		init () {
			this.selected = null;

			// Change this
			setTimeout(() => (this.selected = this.value), 100);
		},

		range (from, to) {
			const range = [];

			from = from > 0 ? from : 1;

			for (let i = from; i <= to; i++) {
				range.push(i);
			}

			return range;
		}
	}

};