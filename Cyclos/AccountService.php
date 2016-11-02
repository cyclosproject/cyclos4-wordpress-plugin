<?php namespace Cyclos;

/**
 * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html
 * 
 * Generated with Cyclos 4.7
 * 
 * WARNING: The API is subject to change between revision versions
 * (for example, 4.5 to 4.6).
 */
class AccountService extends Service {

    function __construct() {
        parent::__construct('accountService');
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.accounts.AccountHistoryQuery
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#exportAccountHistoryToCSV(org.cyclos.model.banking.accounts.AccountHistoryQuery)
     */
    public function exportAccountHistoryToCSV($params) {
        return $this->run('exportAccountHistoryToCSV', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.accounts.AccountBalanceLimitsOverviewQuery
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#exportAccountLimitsToCSV(org.cyclos.model.banking.accounts.AccountBalanceLimitsOverviewQuery)
     */
    public function exportAccountLimitsToCSV($params) {
        return $this->run('exportAccountLimitsToCSV', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.accounts.AccountHistoriesOverviewQuery
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#exportHistoriesOverviewToCSV(org.cyclos.model.banking.accounts.AccountHistoriesOverviewQuery)
     */
    public function exportHistoriesOverviewToCSV($params) {
        return $this->run('exportHistoriesOverviewToCSV', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.accounts.UserWithBalanceQuery
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#exportUserBalanceToCSV(org.cyclos.model.banking.accounts.UserWithBalanceQuery)
     */
    public function exportUserBalanceToCSV($params) {
        return $this->run('exportUserBalanceToCSV', array($params));
    }
    
    /**

     * @return Java type: org.cyclos.model.banking.accounts.AccountBalanceLimitsOverviewData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#getAccountBalanceLimitsOverviewData()
     */
    public function getAccountBalanceLimitsOverviewData() {
        return $this->run('getAccountBalanceLimitsOverviewData', array());
    }
    
    /**

     * @return Java type: org.cyclos.model.banking.accounts.AccountHistoriesOverviewData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#getAccountHistoriesOverviewData()
     */
    public function getAccountHistoriesOverviewData() {
        return $this->run('getAccountHistoriesOverviewData', array());
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.accounts.AccountHistoriesOverviewQuery
     * @return Java type: org.cyclos.model.banking.accounts.AccountHistoriesOverviewStatusVO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#getAccountHistoriesOverviewStatus(org.cyclos.model.banking.accounts.AccountHistoriesOverviewQuery)
     */
    public function getAccountHistoriesOverviewStatus($params) {
        return $this->run('getAccountHistoriesOverviewStatus', array($params));
    }
    
    /**
     * @param account Java type: org.cyclos.model.banking.accounts.AccountVO     * @param rateVisibility Java type: org.cyclos.model.banking.rates.RateVisibility
     * @return Java type: org.cyclos.model.banking.accounts.AccountHistoryData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#getAccountHistoryData(org.cyclos.model.banking.accounts.AccountVO,%20org.cyclos.model.banking.rates.RateVisibility)
     */
    public function getAccountHistoryData($account, $rateVisibility) {
        return $this->run('getAccountHistoryData', array($account, $rateVisibility));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.accounts.AccountHistoryQuery
     * @return Java type: org.cyclos.model.banking.accounts.AccountHistoryStatusVO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#getAccountHistoryStatus(org.cyclos.model.banking.accounts.AccountHistoryQuery)
     */
    public function getAccountHistoryStatus($params) {
        return $this->run('getAccountHistoryStatus', array($params));
    }
    
    /**
     * @param account Java type: org.cyclos.model.banking.accounts.AccountVO     * @param dateTime Java type: org.cyclos.utils.DateTime     * @param rateVisibility Java type: org.cyclos.model.banking.rates.RateVisibility
     * @return Java type: org.cyclos.model.banking.accounts.AccountStatusVO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#getAccountStatus(org.cyclos.model.banking.accounts.AccountVO,%20org.cyclos.utils.DateTime,%20org.cyclos.model.banking.rates.RateVisibility)
     */
    public function getAccountStatus($account, $dateTime, $rateVisibility) {
        return $this->run('getAccountStatus', array($account, $dateTime, $rateVisibility));
    }
    
    /**
     * @param account Java type: org.cyclos.model.banking.accounts.AccountVO     * @param dateTime Java type: org.cyclos.utils.DateTime
     * @return Java type: org.cyclos.model.banking.accounts.AccountWithStatusVO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#getAccountWithStatus(org.cyclos.model.banking.accounts.AccountVO,%20org.cyclos.utils.DateTime)
     */
    public function getAccountWithStatus($account, $dateTime) {
        return $this->run('getAccountWithStatus', array($account, $dateTime));
    }
    
    /**
     * @param owner Java type: org.cyclos.model.banking.accounts.InternalAccountOwner     * @param dateTime Java type: org.cyclos.utils.DateTime
     * @return Java type: java.util.List
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#getAccountsSummary(org.cyclos.model.banking.accounts.InternalAccountOwner,%20org.cyclos.utils.DateTime)
     */
    public function getAccountsSummary($owner, $dateTime) {
        return $this->run('getAccountsSummary', array($owner, $dateTime));
    }
    
    /**
     * @param account Java type: org.cyclos.model.banking.accounts.AccountVO
     * @return Java type: org.cyclos.model.banking.accounts.AccountPermissionsData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#getPermissions(org.cyclos.model.banking.accounts.AccountVO)
     */
    public function getPermissions($account) {
        return $this->run('getPermissions', array($account));
    }
    
    /**
     * @param account Java type: org.cyclos.model.banking.accounts.AccountVO
     * @return Java type: org.cyclos.model.banking.accounts.UserAccountLimitData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#getUserAccountLimitData(org.cyclos.model.banking.accounts.AccountVO)
     */
    public function getUserAccountLimitData($account) {
        return $this->run('getUserAccountLimitData', array($account));
    }
    
    /**
     * @param userLocator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: java.util.List
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#getUserAccountsLimits(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getUserAccountsLimits($userLocator) {
        return $this->run('getUserAccountsLimits', array($userLocator));
    }
    
    /**

     * @return Java type: org.cyclos.model.banking.accounts.UserWithBalanceSearchData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#getUserWithBalanceSearchData()
     */
    public function getUserWithBalanceSearchData() {
        return $this->run('getUserWithBalanceSearchData', array());
    }
    
    /**

     * @return Java type: boolean
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#hasAccessibleAccounts()
     */
    public function hasAccessibleAccounts() {
        return $this->run('hasAccessibleAccounts', array());
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.banking.accounts.AccountWithCurrencyVO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.accounts.AccountHistoryQuery
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#printAccountHistory(org.cyclos.model.banking.accounts.AccountHistoryQuery)
     */
    public function printAccountHistory($params) {
        return $this->run('printAccountHistory', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.accounts.AccountBalanceLimitsOverviewQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#searchAccountBalanceLimitsOverview(org.cyclos.model.banking.accounts.AccountBalanceLimitsOverviewQuery)
     */
    public function searchAccountBalanceLimitsOverview($params) {
        return $this->run('searchAccountBalanceLimitsOverview', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.accounts.AccountHistoriesOverviewQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#searchAccountHistoriesOverview(org.cyclos.model.banking.accounts.AccountHistoriesOverviewQuery)
     */
    public function searchAccountHistoriesOverview($params) {
        return $this->run('searchAccountHistoriesOverview', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.accounts.AccountHistoryQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#searchAccountHistory(org.cyclos.model.banking.accounts.AccountHistoryQuery)
     */
    public function searchAccountHistory($params) {
        return $this->run('searchAccountHistory', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.accounts.UserWithBalanceQuery
     * @return Java type: org.cyclos.model.banking.accounts.UsersWithBalancesResult
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#searchUsersWithBalances(org.cyclos.model.banking.accounts.UserWithBalanceQuery)
     */
    public function searchUsersWithBalances($params) {
        return $this->run('searchUsersWithBalances', array($params));
    }
    
    /**
     * @param data Java type: org.cyclos.model.banking.accounts.AccountLimitDTO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/AccountService.html#setBalanceLimit(org.cyclos.model.banking.accounts.AccountLimitDTO)
     */
    public function setBalanceLimit($data) {
        $this->run('setBalanceLimit', array($data));
    }
    
}