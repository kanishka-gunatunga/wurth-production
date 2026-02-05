@include('layouts.dashboard-header')
<div class="container-fluid">
            <div class="main-wrapper">

                <form class="" action="" method="post">
                @csrf
                <div class="row d-flex justify-content-between">
                    <div class="col-lg-6 col-12">
                        <h1 class="header-title">Access Control</h1>
                    </div>
                   
                </div>

                    <div class="mb-4 col-12 col-lg-6">
                        <label for="head-of-division-select" class="form-label custom-input-label">User
                            Level
                        </label>
                        <select class="form-select custom-input" aria-label="Default select example"
                            id="head-of-division-select" name="user_role">
                            <option value="1">System Administrator</option> 
                            <option value="2">Head of Division</option>
                            <option value="3">Regional Sales Manager</option>
                            <option value="4">Area Sales Manager</option> 
                            <option value="5">Team Leader</option>
                            <option value="6">ADM (Sales Rep)</option>
                            <option value="7">Finance Manager</option>
                            <option value="8">Recovery Manager</option>
                        </select>
                    </div>


                    <div class="row" id="role_permissions_admin" style="display:none;">
                        <div class="col-md-3 ps-4">
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="dashboard_permission" value="dashboard">
                                <label class="form-check-label" for="dashboard_permission">Dashboard</label>
                            </div>
                            </div>
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="um_permission" value="user-Management">
                                <label class="form-check-label" for="um_permission">User Management</label>
                            </div>
                            </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="umadd_permission" value="add-user">
                                    <label class="form-check-label" for="umadd_permission">Add User</label>
                                </div>
                                </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="umedit_permission" value="edit-user">
                                    <label class="form-check-label" for="umedit_permission">Edit User</label>
                                </div>
                                </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="umstatus_permission" value="status-change-user">
                                    <label class="form-check-label" for="umstatus_permission">Activate/Deactivate User</label>
                                </div>
                                </div>
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="ac_permission" value="access-control">
                                <label class="form-check-label" for="ac_permission">Access Control</label>
                            </div>
                            </div>
                             <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="cus_permission" value="customers">
                                <label class="form-check-label" for="cus_permission">Customers</label>
                            </div>
                            </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="cusall_permission" value="all-customers">
                                    <label class="form-check-label" for="cusall_permission">All Customers</label>
                                </div>
                                </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="cusalledit_permission" value="all-customers-edit">
                                        <label class="form-check-label" for="cusalledit_permission">Edit Customer</label>
                                    </div>
                                    </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="cusallview_permission" value="all-customers-view">
                                        <label class="form-check-label" for="cusallview_permission">View Customer</label>
                                    </div>
                                    </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="custemp_permission" value="temp-customers">
                                    <label class="form-check-label" for="custemp_permission">Temp Customers</label>
                                </div>
                                </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="custempedit_permission" value="temp-customers-edit">
                                        <label class="form-check-label" for="custempedit_permission">Edit Temp Customer</label>
                                    </div>
                                    </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="cusadd_permission" value="add-customer">
                                    <label class="form-check-label" for="cusadd_permission">Add Customer</label>
                                </div>
                                </div>
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="dm_permission" value="division-management">
                                <label class="form-check-label" for="dm_permission">Division Management</label>
                            </div>
                            </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="dmadd_permission" value="add-division">
                                    <label class="form-check-label" for="dmadd_permission">Add Division</label>
                                </div>
                                </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="dmedit_permission" value="edit-division">
                                    <label class="form-check-label" for="dmedit_permission">Edit Division</label>
                                </div>
                                </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="dmstatus_permission" value="status-change-division">
                                    <label class="form-check-label" for="dmstatus_permission">Activate/Deactivate Division</label>
                                </div>
                                </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="dmdelete_permission" value="delete-division">
                                    <label class="form-check-label" for="dmdelete_permission">Delete Division</label>
                                </div>
                                </div>
                                
                        </div>
                        <div class="col-md-3 ps-4">
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="collection_permission" value="collections">
                                <label class="form-check-label" for="collection_permission">Collections</label>
                            </div>
                            </div>
                           
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionall_permission" value="all-collections">
                                    <label class="form-check-label" for="collectionall_permission">All Collections</label>
                                </div>
                                </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionallview_permission" value="all-collections-view">
                                        <label class="form-check-label" for="collectionallview_permission">View Collection</label>
                                    </div>
                                    </div>
                               <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionoutstanding_permission" value="all-outstanding">
                                    <label class="form-check-label" for="collectionoutstanding_permission">All Outstanding</label>
                                </div>
                                </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionreceipts_permission" value="all-receipts">
                                    <label class="form-check-label" for="collectionreceipts_permission">All Receipts</label>
                                </div>
                                </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionreceiptsfinal_permission" value="all-receipts-final">
                                        <label class="form-check-label" for="collectionreceiptsfinal_permission">Final Receipts Invocies</label>
                                    </div>
                                    </div>
                                        <div class="row access-control-checks mx-5">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionreceiptsfinalexport_permission" value="all-receipts-final-export">
                                            <label class="form-check-label" for="collectionreceiptsfinalexport_permission">Export</label>
                                        </div>
                                        </div>
                                        <div class="row access-control-checks mx-5">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionreceiptsfinalsms_permission" value="all-receipts-final-sms">
                                            <label class="form-check-label" for="collectionreceiptsfinalsms_permission">Resend SMS</label>
                                        </div>
                                        </div>
                                        <div class="row access-control-checks mx-5">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionreceiptsfinaldownload_permission" value="all-receipts-final-download">
                                            <label class="form-check-label" for="collectionreceiptsfinaldownload_permission">Download</label>
                                        </div>
                                        </div>
                                        <div class="row access-control-checks mx-5">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionreceiptsfinaledit_permission" value="all-receipts-final-edit">
                                            <label class="form-check-label" for="collectionreceiptsfinaledit_permission">Edit</label>
                                        </div>
                                        </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionreceiptstemporary_permission" value="all-receipts-temporary">
                                        <label class="form-check-label" for="collectionreceiptstemporary_permission">Temporary Receipts Invocies</label>
                                    </div>
                                    </div>
                                     
                                        <div class="row access-control-checks mx-5">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionreceiptstemporarysms_permission" value="all-receipts-temporary-sms">
                                            <label class="form-check-label" for="collectionreceiptstemporarysms_permission">Resend SMS</label>
                                        </div>
                                        </div>
                                        <div class="row access-control-checks mx-5">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionreceiptstemporarydownload_permission" value="all-receipts-temporary-download">
                                            <label class="form-check-label" for="collectionreceiptstemporarydownload_permission">Download</label>
                                        </div>
                                        </div>
                                         <div class="row access-control-checks mx-5">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionreceiptsfinaltemporary_permission" value="all-receipts-temporary-edit">
                                            <label class="form-check-label" for="collectionreceiptsfinaltemporary_permission">Edit</label>
                                        </div>
                                        </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionreceiptsadvanced_permission" value="all-receipts-advanced">
                                        <label class="form-check-label" for="collectionreceiptsadvanced_permission">Temporary Receipts Advanced Payments</label>
                                    </div>
                                    </div>
                                     
                                        <div class="row access-control-checks mx-5">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionreceiptsadvancedsms_permission" value="all-receipts-advanced-sms">
                                            <label class="form-check-label" for="collectionreceiptsadvancedsms_permission">Resend SMS</label>
                                        </div>
                                        </div>
                                        <div class="row access-control-checks mx-5">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionreceiptsadvanceddownload_permission" value="all-receipts-advanced-download">
                                            <label class="form-check-label" for="collectionreceiptsadvanceddownload_permission">Download</label>
                                        </div>
                                        </div>
                                         <div class="row access-control-checks mx-5">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionreceiptsfinaladvanced_permission" value="all-receipts-advanced-edit">
                                            <label class="form-check-label" for="collectionreceiptsfinaladvanced_permission">Edit</label>
                                        </div>
                                        </div>
                                        <div class="row access-control-checks mx-5">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionreceiptsfinaladvancedremove_permission" value="all-receipts-advanced-remove">
                                            <label class="form-check-label" for="collectionreceiptsfinaladvancedremove_permission">Remove</label>
                                        </div>
                                        </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionpendingreceipts_permission" value="pending-receipts">
                                    <label class="form-check-label" for="collectionpendingreceipts_permission">Pending Receipts</label>
                                </div>
                                </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionadvanced_permission" value="all-advanced-payments">
                                    <label class="form-check-label" for="collectionadvanced_permission">All Advanced Payments</label>
                                </div>
                                </div>
                                <div class="row access-control-checks mx-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="collectionaddnew_permission" value="all-collections-add">
                                        <label class="form-check-label" for="collectionaddnew_permission">Add New Collection</label>
                                    </div>
                                    </div>    
                                
                        </div>

                        <div class="col-md-3 ps-4">
                           
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="inquaries_permission" value="inquaries">
                                <label class="form-check-label" for="inquaries_permission">Inquaries</label>
                            </div>
                            </div>
                               
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="inquariesstatus_permission" value="status-change-inquary">
                                    <label class="form-check-label" for="inquariesstatus_permission">Approve/Reject Inquary</label>
                                </div>
                                </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="inquariesdownload_permission" value="inquary-download">
                                    <label class="form-check-label" for="inquariesdownload_permission">Download</label>
                                </div>
                                </div>
                            
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="returncheques_permission" value="return-cheques">
                                <label class="form-check-label" for="returncheques_permission">Return Cheques</label>
                            </div>
                            </div>
                               
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="returnchequesimport_permission" value="return-cheques-import">
                                    <label class="form-check-label" for="returnchequesimport_permission">Import Cheques</label>
                                </div>
                                </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="returnchequesview_permission" value="return-cheques-view">
                                    <label class="form-check-label" for="returnchequesview_permission">View Cheque</label>
                                </div>
                                </div>   
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="returnchequesadd_permission" value="return-cheques-add">
                                    <label class="form-check-label" for="returnchequesadd_permission">Add Cheques</label>
                                </div>
                                </div> 
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="writeoffback_permission" value="writeoff-writeback">
                                <label class="form-check-label" for="writeoffback_permission">Write-Off & Write-Back</label>
                            </div>
                            </div>
                               
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="writeoffbackadd_permission" value="writeoff-writeback-add">
                                    <label class="form-check-label" for="writeoffbackadd_permission">Add Write-Off & Write-Back</label>
                                </div>
                                </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="writeoffbackview_permission" value="writeoff-writeback-view">
                                    <label class="form-check-label" for="writeoffbackview_permission">View Write-Off & Write-Back</label>
                                </div>
                                </div>   
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="writeoffbackdownload_permission" value="writeoff-writeback-download">
                                    <label class="form-check-label" for="writeoffbackdownload_permission">Download Write-Off & Write-Back</label>
                                </div>
                                </div>    
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="setoff_permission" value="setoff">
                                <label class="form-check-label" for="setoff_permission">Set - Off</label>
                            </div>
                            </div>
                               
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="setoffadd_permission" value="setoff-add">
                                    <label class="form-check-label" for="setoffadd_permission">Add Set - Off</label>
                                </div>
                                </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="setoffview_permission" value="setoff-view">
                                    <label class="form-check-label" for="setoffview_permission">View Set - Off</label>
                                </div>
                                </div>   
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="setoffdownload_permission" value="setoff-download">
                                    <label class="form-check-label" for="setoffdownload_permission">Download Set - Off</label>
                                </div>
                                </div> 
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="notifications_permission" value="notifications">
                                <label class="form-check-label" for="notifications_permission">Notifications</label>
                            </div>
                            </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="notificationscreate_permission" value="notification-create">
                                    <label class="form-check-label" for="notificationscreate_permission">Create Notification</label>
                                </div>
                                </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="notificationspayment_permission" value="notifications-payment">
                                    <label class="form-check-label" for="notificationspayment_permission">Payment Notifications</label>
                                </div>
                                </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="notificationspaymentview_permission" value="notifications-payment-view">
                                        <label class="form-check-label" for="notificationspaymentview_permission">View Payment Notification</label>
                                    </div>
                                    </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="notificationssystem_permission" value="notifications-system">
                                    <label class="form-check-label" for="notificationssystem_permission">System Notifications </label>
                                </div>
                                </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="notificationssystemview_permission" value="notifications-system-view">
                                        <label class="form-check-label" for="notificationssystemview_permission">View System Notification</label>
                                    </div>
                                    </div>
                        </div>

                         <div class="col-md-3 ps-4">
                           
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="deposits_permission" value="deposits">
                                <label class="form-check-label" for="deposits_permission">Deposits</label>
                            </div>
                            </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="depositsfinancecash_permission" value="deposits-finance-cash">
                                    <label class="form-check-label" for="depositsfinancecash_permission">View All Finance Cash</label>
                                </div>
                                </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="depositsfinancecashstatus_permission" value="deposits-finance-cash-status">
                                        <label class="form-check-label" for="depositsfinancecashstatus_permission">Approve/Reject</label>
                                    </div>
                                    </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="depositsfinancecashdownload_permission" value="deposits-finance-cash-download">
                                        <label class="form-check-label" for="depositsfinancecashdownload_permission">Download</label>
                                    </div>
                                    </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="ddepositsfinancecashview_permission" value="deposits-finance-cash-view">
                                        <label class="form-check-label" for="ddepositsfinancecashview_permission">View</label>
                                    </div>
                                    </div> 
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="depositsfinancecheque_permission" value="deposits-finance-cheque">
                                    <label class="form-check-label" for="depositsfinancecheque_permission">View All Finance Cheque</label>
                                </div>
                                </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="depositsfinancechequestatus_permission" value="deposits-finance-cheque-status">
                                        <label class="form-check-label" for="depositsfinancechequestatus_permission">Approve/Reject</label>
                                    </div>
                                    </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="depositsfinancechequedownload_permission" value="deposits-finance-cheque-download">
                                        <label class="form-check-label" for="depositsfinancechequedownload_permission">Download</label>
                                    </div>
                                    </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="ddepositsfinancechequeview_permission" value="deposits-finance-cheque-view">
                                        <label class="form-check-label" for="ddepositsfinancechequeview_permission">View</label>
                                    </div>
                                    </div> 
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="depositscash_permission" value="deposits-cash">
                                    <label class="form-check-label" for="depositscash_permission">View All Cash Deposits</label>
                                </div>
                                </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="depositscashstatus_permission" value="deposits-cash-status">
                                        <label class="form-check-label" for="depositscashstatus_permission">Approve/Reject</label>
                                    </div>
                                    </div>  
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="depositscashdownload_permission" value="deposits-cash-download">
                                        <label class="form-check-label" for="depositscashdownload_permission">Download</label>
                                    </div>
                                    </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="depositscashview_permission" value="deposits-cash-view">
                                        <label class="form-check-label" for="depositscashview_permission">View</label>
                                    </div>
                                    </div> 
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="depositscheque_permission" value="deposits-cheque">
                                    <label class="form-check-label" for="depositscheque_permission">View All Cheque Deposits</label>
                                </div>
                                </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="depositschequestatus_permission" value="deposits-cheque-status">
                                        <label class="form-check-label" for="depositschequestatus_permission">Approve/Reject</label>
                                    </div>
                                    </div>  
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="depositschequedownload_permission" value="deposits-cheque-download">
                                        <label class="form-check-label" for="depositschequedownload_permission">Download</label>
                                    </div>
                                    </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="depositschequeview_permission" value="deposits-cheque-view">
                                        <label class="form-check-label" for="depositschequeview_permission">View</label>
                                    </div>
                                    </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="depositsfundtransfer_permission" value="deposits-fund-transfer">
                                    <label class="form-check-label" for="depositsfundtransfer_permission">View All Fund Transfer</label>
                                </div>
                                </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="depositsfundtransferstatus_permission" value="deposits-fund-transfer-status">
                                        <label class="form-check-label" for="depositsfundtransferstatus_permission">Approve/Reject</label>
                                    </div>
                                    </div>  
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="depositsfundtransferdownload_permission" value="deposits-fund-transfer-download">
                                        <label class="form-check-label" for="depositsfundtransferdownload_permission">Download</label>
                                    </div>
                                    </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="depositsfundtransferview_permission" value="deposits-fund-transfer-view">
                                        <label class="form-check-label" for="depositsfundtransferview_permission">View</label>
                                    </div>
                                    </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="depositscardpayment_permission" value="deposits-card-payment">
                                    <label class="form-check-label" for="depositscardpayment_permission">View All Card Payment</label>
                                </div>
                                </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="depositscardpaymentstatus_permission" value="deposits-card-payment-status">
                                        <label class="form-check-label" for="depositscardpaymentstatus_permission">Approve/Reject</label>
                                    </div>
                                    </div>  
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="depositscardpaymentdownload_permission" value="deposits-card-payment-download">
                                        <label class="form-check-label" for="depositscardpaymentdownload_permission">Download</label>
                                    </div>
                                    </div>
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="depositscardpaymentview_permission" value="deposits-card-payment-view">
                                        <label class="form-check-label" for="depositscardpaymentview_permission">View</label>
                                    </div>
                                    </div>
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="upload_permission" value="upload">
                                <label class="form-check-label" for="upload_permission">Upload</label>
                            </div>
                            </div>
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="reports_permission" value="reports">
                                <label class="form-check-label" for="reports_permission">Reports</label>
                            </div>
                            </div>
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="settings_permission" value="settings">
                                <label class="form-check-label" for="settings_permission">Settings</label>
                            </div>
                            </div>
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="security_permission" value="security">
                                <label class="form-check-label" for="security_permission">Security</label>
                            </div>
                            </div>   
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="securitybackup_permission" value="security-backup">
                                    <label class="form-check-label" for="securitybackup_permission">Backup</label>
                                </div>
                                </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="securityactivity_permission" value="security-activity">
                                    <label class="form-check-label" for="securityactivity_permission">Activity Log</label>
                                </div>
                                </div>   
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="securityactivityview_permission" value="security-activity-view">
                                        <label class="form-check-label" for="securityactivityview_permission">View Activity Log</label>
                                    </div>
                                    </div>   
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="securitylocked_permission" value="security-locked">
                                    <label class="form-check-label" for="securitylocked_permission">Locked Users</label>
                                </div>
                                </div> 
                                    <div class="row access-control-checks mx-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="securitylockedunlock_permission" value="security-locked-unlock">
                                        <label class="form-check-label" for="securitylockedunlock_permission">Unlock User</label>
                                    </div>
                                    </div> 
                           
                        </div>
                    </div>
                    <div class="row" id="role_permissions_adm" style="display:none;">
                        <div class="col-md-3 ps-4">
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="dashboard_permission_adm" value="dashboard">
                                <label class="form-check-label" for="dashboard_permission_adm">Dashboard</label>
                            </div>
                            </div>
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="adm_collections_permission" value="collections">
                                <label class="form-check-label" for="adm_collections_permission">Collections</label>
                            </div>
                            </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="adm_collectionsbulk_permission" value="bulk-collection">
                                    <label class="form-check-label" for="adm_collectionsbulk_permission">Bulk Payment</label>
                                </div>
                                </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="adm_collectionsadvanced_permission" value="advanced-payment">
                                    <label class="form-check-label" for="adm_collectionsadvanced_permission">Advanced Payment</label>
                                </div>
                                </div>
                                <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="adm_collectionsar_permission" value="all-reciepts">
                                    <label class="form-check-label" for="adm_collectionsar_permission">All Reciepts</label>
                                </div>
                                </div>
                                 <div class="row access-control-checks mx-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="adm_collectionsinvocies_permission" value="all-invocies">
                                    <label class="form-check-label" for="adm_collectionsinvocies_permission">All Invocies</label>
                                </div>
                                </div>
                            <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="adm_customers_permission" value="customers">
                                <label class="form-check-label" for="adm_customers_permission">Customers</label>
                            </div>
                            </div>
                              <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="adm_inquiries_permission" value="inquiries">
                                <label class="form-check-label" for="adm_inquiries_permission">Inquiries</label>
                            </div>
                            </div>
                             <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="adm_reminders_permission" value="reminders">
                                <label class="form-check-label" for="adm_reminders_permission">Reminders</label>
                            </div>
                            </div>
                             <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="adm_deposit_permission" value="deposit">
                                <label class="form-check-label" for="adm_deposit_permission">Deposit</label>
                            </div>
                            </div>
                              <div class="row access-control-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="adm_profile_permission" value="profile">
                                <label class="form-check-label" for="adm_profile_permission">Profile</label>
                            </div>
                            </div>
                                
                        </div>
                    </div>   


                <div class="col-12 d-flex justify-content-end division-action-btn gap-3">
                    <button class="btn btn-danger submit">Update Permissions</button>
                </div>
            </div>
            </form>
        </div>



</body>

</html>
@include('layouts.footer2')



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>

    document.addEventListener('DOMContentLoaded', function () {
        const userRoleSelect = document.getElementById('head-of-division-select');
        if (userRoleSelect) {
            userRoleSelect.addEventListener('change', fetchPermissions);
        }
        
        fetchPermissions();


        function fetchPermissions() {
            const userRole = document.getElementById('head-of-division-select').value;
            let $permissionContainer;

            if (userRole === "6" || userRole === "8") {
                $permissionContainer = $('#role_permissions_adm');
            } else {
                $permissionContainer = $('#role_permissions_admin');
            }

            // Clear all checkboxes
            $permissionContainer.find('input[type="checkbox"]').prop('checked', false);

            $.ajax({
                url: '{{url('get-role-permissions')}}',
                type: 'POST',
                dataType: 'json',
                data: {
                    user_role: userRole,
                    _token: '{{ csrf_token() }}'
                },
                success: function(permissions) {
                    if (Array.isArray(permissions)) {
    
                        permissions.forEach(function(permissionValue) {
                            $permissionContainer.find('input[type="checkbox"][value="' + permissionValue + '"]').prop('checked', true);
                        });

                        $permissionContainer.find('input[type="checkbox"]:checked').each(function() {
                            $(this).trigger('change');
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching permissions:', xhr.responseText);

                }
            });
        }
        

    });
</script>

<script>

    $(document).ready(function() {

        function getPermissionLevel($element) {
            const parentRow = $element.closest('.row.access-control-checks');
            let level = 0;
            if (parentRow.hasClass('mx-2')) {
                level = 1;
            } else if (parentRow.hasClass('mx-4')) {
                level = 2;
            } else if (parentRow.hasClass('mx-5')) {
                level = 3; 
            }
            return level;
        }
        


        $('#role_permissions_admin').on('change', 'input[type="checkbox"]', function() {
            const $this = $(this);
            const isChecked = $this.prop('checked');
            const currentLevel = getPermissionLevel($this);
            const parentRow = $this.closest('.row.access-control-checks');
            
            let $nextRow = parentRow.next('.row.access-control-checks');
            
            while ($nextRow.length && getPermissionLevel($nextRow.find('input[type="checkbox"]')) > currentLevel) {
                $nextRow.find('input[type="checkbox"]').prop('checked', isChecked);
                
                $nextRow = $nextRow.next('.row.access-control-checks');
            }
            
            if (isChecked) {
                updateParentCheckbox($this);
            }
        });

        $('#role_permissions_adm').on('change', 'input[type="checkbox"]', function() {
            const $this = $(this);
            const isChecked = $this.prop('checked');
            const currentLevel = getPermissionLevel($this);
            const parentRow = $this.closest('.row.access-control-checks');
            
            let $nextRow = parentRow.next('.row.access-control-checks');
            
            while ($nextRow.length && getPermissionLevel($nextRow.find('input[type="checkbox"]')) > currentLevel) {
                $nextRow.find('input[type="checkbox"]').prop('checked', isChecked);
                
                $nextRow = $nextRow.next('.row.access-control-checks');
            }
            
            if (isChecked) {
                updateParentCheckbox($this);
            }
        });

        function updateParentCheckbox($childCheckbox) {
            let $currentChild = $childCheckbox.closest('.row.access-control-checks');
            let childLevel = getPermissionLevel($childCheckbox);
            
            while (childLevel > 0) {
                let $parentCheckbox = null;
                let $prevRow = $currentChild.prev('.row.access-control-checks');
                
                while ($prevRow.length) {
                    const prevLevel = getPermissionLevel($prevRow.find('input[type="checkbox"]'));
                    
                    if (prevLevel === childLevel - 1) {
                        $parentCheckbox = $prevRow.find('input[type="checkbox"]');
                        break;
                    } else if (prevLevel < childLevel - 1) {
                     
                        break;
                    }
                    $prevRow = $prevRow.prev('.row.access-control-checks');
                }
                
                if ($parentCheckbox && $parentCheckbox.length) {
                    const $siblingCheckboxes = [];
                    let $checkRow = $parentCheckbox.closest('.row.access-control-checks').next('.row.access-control-checks');

                    while ($checkRow.length && getPermissionLevel($checkRow.find('input[type="checkbox"]')) > getPermissionLevel($parentCheckbox)) {
                        if (getPermissionLevel($checkRow.find('input[type="checkbox"]')) === childLevel) {
                            $siblingCheckboxes.push($checkRow.find('input[type="checkbox"]'));
                        }
                        $checkRow = $checkRow.next('.row.access-control-checks');
                    }

                    const anyChildChecked = $siblingCheckboxes.some(chk => $(chk).prop('checked'));

                    $parentCheckbox.prop('checked', anyChildChecked);

                    $currentChild = $parentCheckbox.closest('.row.access-control-checks');
                    childLevel = getPermissionLevel($parentCheckbox);
                } else {
           
                    break;
                }
            }
        }
        

        $('#role_permissions_admin').on('change', 'input[type="checkbox"]', function() {
            const $this = $(this);
            const isChecked = $this.prop('checked');

            updateParentCheckbox($this);
        });
        

        $('#role_permissions_admin').on('change', 'input[type="checkbox"]', function() {
            const $this = $(this);
            const isChecked = $this.prop('checked');
            const currentLevel = getPermissionLevel($this);
            const parentRow = $this.closest('.row.access-control-checks');
            

            let $nextRow = parentRow.next('.row.access-control-checks');
            

            while ($nextRow.length && getPermissionLevel($nextRow.find('input[type="checkbox"]')) > currentLevel) {
                $nextRow.find('input[type="checkbox"]').prop('checked', isChecked);
                $nextRow = $nextRow.next('.row.access-control-checks');
            }


            updateParentCheckbox($this);
        });

        $('#role_permissions_admin').find('input[type="checkbox"]:checked').each(function() {
            updateParentCheckbox($(this));
        });
        
    });

    $(document).ready(function () {

    function togglePermissionSections() {
        const selectedRole = $('#head-of-division-select').val();
        
        if (selectedRole === '6' || selectedRole === '8') {
            $('#role_permissions_adm').show();
            $('#role_permissions_admin').hide();
        } else {

            $('#role_permissions_admin').show();
            $('#role_permissions_adm').hide();
        }
    }

    togglePermissionSections();

    $('#head-of-division-select').on('change', function () {
        togglePermissionSections();
    });

});
</script>
<script>
    $(document).ready(function() {
        @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
        @endif

        @if(Session::has('fail'))
        toastr.error("{{ Session::get('fail') }}");
        @endif
    });
</script>