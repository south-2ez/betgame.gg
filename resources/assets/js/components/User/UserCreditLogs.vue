<template>
<div class="main-container dark-grey">
    <div class="m-container3">
        <div class="main-ct temp_div">
            <div class="title2">
                Credit Logs:<span class="title-username"> {{ user.name }} | <b>{{ user.email }}</b></span>
            </div>
            <div class="clearfix"></div>
            <div class="blk-2">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Start Date:</label>
                        <input v-model="date_start" type="text" class="form-control datepicker" id="start-date" placeholder="Credit log Start Date">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>End Date:</label>
                        <input v-model="date_end" type="text" class="form-control datepicker" id="end-date" placeholder="Credit log End Date">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <button type="button" class="btn btn-info btn-filter" @click="filter">Filter</button>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="tab-content">
                        <br />
                        <table id="user-credit-table" class="table table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Log ID</th>
                                    <th>Reference ID</th>
                                    <th>Model</th>
                                    <th>Action</th>
                                    <th>Amount</th>
                                    <th>Previous Credit</th>
                                    <th>New Credits</th>
                                </tr>
                            </thead>
                            <tbody v-if="logs.length == 0">
                                <tr>
                                    <td class="text-center" colspan="8">No Credit Logs Found</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr v-for="log in logs" :key="log.id">
                                    <td v-text="log.created_at"></td>
                                    <td v-text="log.id"></td>
                                    <td v-text="log.reference_id "></td>
                                    <td v-text="log.model"></td>
                                    <td v-text="log.action"></td>
                                    <td v-text="formatAmount(log.amount)" class="text-right"></td>
                                    <td v-text="formatAmount(log.current_credit)" class="text-right"></td>
                                    <td v-text="formatAmount(log.new_credit)" class="text-right"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            'date_start': null,
            'date_end': null
        }
    },
    props: {
        "logs": {
            type: Array,
            default: []
        },
        "user": {
            type: Object,
            default: {}
        },
        "url": {
            type: String,
            default: null
        },
        "start_date": {
            type: String,
            default: null
        },
        "end_date": {
            type: String,
            default: null
        }
    },
    mounted() {
        this.date_start = this.start_date;
        this.date_end = this.end_date;
    },
    methods: {
        formatAmount: function (amount) {
            amount = parseFloat(amount).toFixed(2);
            var num_parts = amount.toString().split(".");
            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            return num_parts.join(".");
        },
        filter: function () {
            let filters = {};
            let url_params = '';

            if ($("#start-date").val()) {
                filters['start_date'] = $("#start-date").val();
            }

            if ($("#end-date").val()) {
                filters['end_date'] = $("#end-date").val();
            }

            if (Object.entries(filters).length > 0) {
                url_params = '?' + $.param(filters);
            }

            window.location.assign(this.url + url_params);
        }
    }
}
</script>

<style scoped>
.title-username {
    color: #d4af37;
}

.text-right {
    text-align: right;
}

#user-credit-table th {
    border: 1px solid #2d2d2d;
}

#user-credit-table tbody {
    border: 1px solid #2d2d2d;
}

.btn-filter {
    margin-top: 26px;
    width: 100%;
}
</style>
