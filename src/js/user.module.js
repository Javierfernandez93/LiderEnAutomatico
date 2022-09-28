import { Http } from '../../src/js/http.module.js';

class User extends Http {
    constructor() {
        super();
    }
    doLogin(data, callback) {
        return this.call('../../app/application/do_login.php', data, callback);
    }
    getBackofficeVars(data, callback) {
        return this.call('../../app/application/get_backoffice_vars.php', data, callback);
    }
    getNotifications(data, callback) {
        return this.call('../../app/application/get_notifications.php', data, callback);
    }
    getBalance(data, callback) {
        return this.call('../../app/application/get_balance.php', data, callback);
    }
    createTransactionRequirement(data, callback) {
        return this.call('../../app/application/create_transaction_requirement.php', data, callback);
    }
    getLastTransactionsRequirement(data, callback) {
        return this.call('../../app/application/get_last_transactions_requirement.php', data, callback);
    }
    getCurrencies(data, callback) {
        return this.call('../../app/application/get_currencies.php', data, callback);
    }
    doWithdraw(data, callback) {
        return this.call('../../app/application/do_withdraw.php', data, callback);
    }
    editWithdrawMethod(data, callback) {
        return this.call('../../app/application/edit_withdraw_method.php', data, callback);
    }
    getWithdraws(data, callback) {
        return this.call('../../app/application/get_withdraws.php', data, callback);
    }
    getMyRange(data, callback) {
        return this.call('../../app/application/getMyRange.php', data, callback);
    }
    getPlans(data, callback) {
        return this.call('../../app/application/get_plans.php', data, callback);
    }
    editProfile(data, callback) {
        return this.call('../../app/application/edit_profile.php', data, callback);
    }
    getCountries(data, callback) {
        return this.call('../../app/application/get_countries.php', data, callback);
    }
    getPaymentMethods(data, callback) {
        return this.call('../../app/application/getPaymentMethods.php', data, callback);
    }
    changePassword(data, callback) {
        return this.call('../../app/application/change_password.php', data, callback);
    }
    recoverPassword(data, callback) {
        return this.call('../../app/application/recover_password.php', data, callback);
    }
    getAuthToChangePassword(data, callback) {
        return this.call('../../app/application/get_auth_to_change_password.php', data, callback);
    }
    doSignup(data, callback) {
        return this.call('../../app/application/do_signup.php', data, callback);
    }
    getProfile(data, callback) {
        return this.call('../../app/application/get_profile.php', data, callback);
    }
    getLastReferrals(data, callback) {
        return this.call('../../app/application/get_last_referrals.php', data, callback);
    }
    getReferrals(data, callback) {
        return this.call('../../app/application/get_referrals.php', data, callback);
    }
    getReferral(data, callback) {
        return this.call('../../app/application/get_referral.php', data, callback);
    }
    getProfits(data, callback) {
        return this.call('../../app/application/get_profits.php', data, callback);
    }
    getProfitStats(data, callback) {
        return this.call('../../app/application/get_profit_stats.php', data, callback);
    }
    getNoticesList(data, callback) {
        return this.call('../../app/application/get_notices_list.php', data, callback);
    }
    getStripeCustomer(data, callback) {
        return this.call('../../app/application/getStripeCustomer.php', data, callback);
    }
    saveCustomerCard(data, callback) {
        return this.call('../../app/application/saveCustomerCard.php', data, callback);
    }
    getPayPalPaymentStatus(data, callback) {
        return this.call('../../app/application/getPayPalPaymentStatus.php', data, callback);
    }
    getStripePublicKey(data, callback) {
        return this.call('../../app/application/getStripePublicKey.php', data, callback);
    }
    registerTransaction(data, callback) {
        return this.call('../../app/application/registerTransaction.php', data, callback);
    }
    getStripePaymentIntervals(data, callback) {
        return this.call('../../app/application/getStripePaymentIntervals.php', data, callback);
    }
    createStripeSuscription(data, callback) {
        return this.call('../../app/application/createStripeSuscription.php', data, callback);
    }
    getTransactionRequirementInvoice(data, callback) {
        return this.call('../../app/application/getTransactionRequirementInvoice.php', data, callback);
    }
    getTransactionRequirementForRegistration(data, callback) {
        return this.call('../../app/application/getTransactionRequirementForRegistration.php', data, callback);
    }
    registerTransaction(data, callback) {
        return this.call('../../app/application/registerTransaction.php', data, callback);
    }
    createStripePayment(data, callback) {
        return this.call('../../app/application/createStripePayment.php', data, callback);
    }
    checkStripeSuscription(data, callback) {
        return this.call('../../app/application/checkStripeSuscription.php', data, callback);
    }
    getAllTransactionsRequirement(data, callback) {
        return this.call('../../app/application/getAllTransactionsRequirement.php', data, callback);
    }
    getTransactionAmmount(data, callback) {
        return this.call('../../app/application/getTransactionAmmount.php', data, callback);
    }
    // callfile
    uploadImageProfile(data, progress, callback) {
        return this.callFile('../../app/application/upload_image_profile.php', data, callback, progress);
    }
    uploadImageFund(data, progress, callback) {
        return this.callFile('../../app/application/uploadImageFund.php', data, callback, progress);
    }
    checkStripePayment(data,callback) {
        return this.call('./../../app/application/checkStripePayment.php', data, callback);
    }
    getReferralsShortData(data,callback) {
        return this.call('./../../app/application/getReferralsShortData.php', data, callback);
    }
    getStripeClientSecret(data,callback) {
        return this.call('./../../app/application/getStripeClientSecret.php', data, callback);
    }
    getToolsList(data,callback) {
        return this.call('./../../app/application/getToolsList.php', data, callback);
    }
    saveInvestor(data,callback) {
        return this.call('./../../app/application/saveInvestor.php', data, callback);
    }
}

export { User }