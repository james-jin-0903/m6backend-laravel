<?php

namespace App\GraphQL\coreDB;

trait Queries
{
/* eslint-disable */
// this is an auto generated file. This will be overwritten

public $getUser = /* GraphQL */ '
  query GetUser($id: ID!) {
    getUser(id: $id) {
      id
      drupalId
      cognitoId
      firstName
      lastName
      location {
        lat
        lng
        address
      }
      email
      phone
      lastVisit
      profilePic
      companies {
        nextToken
      }
      createdAt
      updatedAt
    }
  }
';
public $listUsers = /* GraphQL */ '
  query ListUsers(
    $filter: ModelUserFilterInput
    $limit: Int
    $nextToken: String
  ) {
    listUsers(filter: $filter, limit: $limit, nextToken: $nextToken) {
      items {
        id
        drupalId
        cognitoId
        firstName
        lastName
        email
        phone
        lastVisit
        profilePic
        createdAt
        updatedAt
      }
      nextToken
    }
  }
';
public $getUserCompany = /* GraphQL */ '
  query GetUserCompany($id: ID!) {
    getUserCompany(id: $id) {
      id
      userID
      companyID
      active
      joinStatus
      userWhoInvited
      user {
        id
        drupalId
        cognitoId
        firstName
        lastName
        email
        phone
        lastVisit
        profilePic
        createdAt
        updatedAt
      }
      company {
        id
        legacyID
        name
        logo
        status
        legalCompanyName
        email
        phone
        qtySurveyAns
        website
        companyInsuranceStatus
        preferredOSDHPDClass
        types
        regions
        unspcs
        naics
        createdAt
        updatedAt
      }
      createdAt
      updatedAt
    }
  }
';
public $listUserCompanys = /* GraphQL */ '
  query ListUserCompanys(
    $filter: ModeluserCompanyFilterInput
    $limit: Int
    $nextToken: String
  ) {
    listUserCompanys(filter: $filter, limit: $limit, nextToken: $nextToken) {
      items {
        id
        userID
        companyID
        active
        joinStatus
        userWhoInvited
        createdAt
        updatedAt
      }
      nextToken
    }
  }
';
public $getInvite = /* GraphQL */ '
  query GetInvite($id: ID!) {
    getInvite(id: $id) {
      id
      firstName
      lastName
      email
      companyID
      tokenUsed
      sent
      createdAt
      updatedAt
    }
  }
';
public $listInvites = /* GraphQL */ '
  query ListInvites(
    $filter: ModelInviteFilterInput
    $limit: Int
    $nextToken: String
  ) {
    listInvites(filter: $filter, limit: $limit, nextToken: $nextToken) {
      items {
        id
        firstName
        lastName
        email
        companyID
        tokenUsed
        sent
        createdAt
        updatedAt
      }
      nextToken
    }
  }
';
public $getCompany = /* GraphQL */ '
  query GetCompany($id: ID!) {
    getCompany(id: $id) {
      id
      legacyID
      name
      logo
      status
      legalCompanyName
      email
      phone
      qtySurveyAns
      website
      links {
        uat
        prod
      }
      applications {
        nextToken
      }
      locations {
        id
        name
        mainLocation
        address
        postalCode
        city
        county
        stateCode
        stateFull
        country
        lat
        lng
        locationType
      }
      companyInsuranceStatus
      preferredOSDHPDClass
      projectCapability {
        to
        from
      }
      projectSize {
        to
        from
      }
      socialMediaLinks {
        icon
        name
        link
      }
      types
      regions
      unspcs
      naics
      users {
        nextToken
      }
      createdAt
      updatedAt
    }
  }
';
public $listCompanys = /* GraphQL */ '
  query ListCompanys(
    $filter: ModelCompanyFilterInput
    $limit: Int
    $nextToken: String
  ) {
    listCompanys(filter: $filter, limit: $limit, nextToken: $nextToken) {
      items {
        id
        legacyID
        name
        logo
        status
        legalCompanyName
        email
        phone
        qtySurveyAns
        website
        companyInsuranceStatus
        preferredOSDHPDClass
        types
        regions
        unspcs
        naics
        createdAt
        updatedAt
      }
      nextToken
    }
  }
';
public $getProjectInfo = /* GraphQL */ '
  query GetProjectInfo($id: ID!) {
    getProjectInfo(id: $id) {
      id
      preferredOshpd
      projCapAmountFrom
      projCapAmountTo
      projectCount
      sweetSpotFrom
      sweetSpotTo
      annualRevenue
      projectSize
      status
      companyRating
      insuranceStatus
      company {
        id
        legacyID
        name
        logo
        status
        legalCompanyName
        email
        phone
        qtySurveyAns
        website
        companyInsuranceStatus
        preferredOSDHPDClass
        types
        regions
        unspcs
        naics
        createdAt
        updatedAt
      }
      createdAt
      updatedAt
    }
  }
';
public $listProjectInfos = /* GraphQL */ '
  query ListProjectInfos(
    $filter: ModelProjectInfoFilterInput
    $limit: Int
    $nextToken: String
  ) {
    listProjectInfos(filter: $filter, limit: $limit, nextToken: $nextToken) {
      items {
        id
        preferredOshpd
        projCapAmountFrom
        projCapAmountTo
        projectCount
        sweetSpotFrom
        sweetSpotTo
        annualRevenue
        projectSize
        status
        companyRating
        insuranceStatus
        createdAt
        updatedAt
      }
      nextToken
    }
  }
';
public $getLocation = /* GraphQL */ '
  query GetLocation($id: ID!) {
    getLocation(id: $id) {
      id
      region
      state
      street
      city
      postalCode
      companyID
      company {
        id
        legacyID
        name
        logo
        status
        legalCompanyName
        email
        phone
        qtySurveyAns
        website
        companyInsuranceStatus
        preferredOSDHPDClass
        types
        regions
        unspcs
        naics
        createdAt
        updatedAt
      }
      createdAt
      updatedAt
    }
  }
';
public $listLocations = /* GraphQL */ '
  query ListLocations(
    $filter: ModelLocationFilterInput
    $limit: Int
    $nextToken: String
  ) {
    listLocations(filter: $filter, limit: $limit, nextToken: $nextToken) {
      items {
        id
        region
        state
        street
        city
        postalCode
        companyID
        createdAt
        updatedAt
      }
      nextToken
    }
  }
';
public $getGeneralInfo = /* GraphQL */ '
  query GetGeneralInfo($id: ID!) {
    getGeneralInfo(id: $id) {
      id
      description
      social
      createdAt
      updatedAt
    }
  }
';
public $listGeneralInfos = /* GraphQL */ '
  query ListGeneralInfos(
    $filter: ModelGeneralInfoFilterInput
    $limit: Int
    $nextToken: String
  ) {
    listGeneralInfos(filter: $filter, limit: $limit, nextToken: $nextToken) {
      items {
        id
        description
        social
        createdAt
        updatedAt
      }
      nextToken
    }
  }
';
public $getCompaniesApplications = /* GraphQL */ '
  query GetCompaniesApplications($id: ID!) {
    getCompaniesApplications(id: $id) {
      id
      companyID
      applicationID
      company {
        id
        legacyID
        name
        logo
        status
        legalCompanyName
        email
        phone
        qtySurveyAns
        website
        companyInsuranceStatus
        preferredOSDHPDClass
        types
        regions
        unspcs
        naics
        createdAt
        updatedAt
      }
      application {
        id
        name
        createdAt
        updatedAt
      }
      createdAt
      updatedAt
    }
  }
';
public $listCompaniesApplicationss = /* GraphQL */ '
  query ListCompaniesApplicationss(
    $filter: ModelCompaniesApplicationsFilterInput
    $limit: Int
    $nextToken: String
  ) {
    listCompaniesApplicationss(
      filter: $filter
      limit: $limit
      nextToken: $nextToken
    ) {
      items {
        id
        companyID
        applicationID
        createdAt
        updatedAt
      }
      nextToken
    }
  }
';
public $getApplication = /* GraphQL */ '
  query GetApplication($id: ID!) {
    getApplication(id: $id) {
      id
      name
      companies {
        nextToken
      }
      createdAt
      updatedAt
    }
  }
';
public $listApplications = /* GraphQL */ '
  query ListApplications(
    $filter: ModelApplicationFilterInput
    $limit: Int
    $nextToken: String
  ) {
    listApplications(filter: $filter, limit: $limit, nextToken: $nextToken) {
      items {
        id
        name
        createdAt
        updatedAt
      }
      nextToken
    }
  }
';
public $getRapidTicket = /* GraphQL */ '
  query GetRapidTicket($id: ID!) {
    getRapidTicket(id: $id) {
      id
      imgLink
      items {
        id
        title
        text
        x
        y
        rotation
        selected
      }
      company {
        id
        email
        legalCompanyName
        name
        phone
      }
      user {
        id
        email
        firstName
        lastName
      }
      createdAt
      updatedAt
    }
  }
';
public $listRapidTickets = /* GraphQL */ '
  query ListRapidTickets(
    $filter: ModelRapidTicketFilterInput
    $limit: Int
    $nextToken: String
  ) {
    listRapidTickets(filter: $filter, limit: $limit, nextToken: $nextToken) {
      items {
        id
        imgLink
        createdAt
        updatedAt
      }
      nextToken
    }
  }
';
public $findUserByEmail = /* GraphQL */ '
  query FindUserByEmail(
    $email: String
    $sortDirection: ModelSortDirection
    $filter: ModelUserFilterInput
    $limit: Int
    $nextToken: String
  ) {
    findUserByEmail(
      email: $email
      sortDirection: $sortDirection
      filter: $filter
      limit: $limit
      nextToken: $nextToken
    ) {
      items {
        id
        drupalId
        cognitoId
        firstName
        lastName
        email
        phone
        lastVisit
        profilePic
        createdAt
        updatedAt
      }
      nextToken
    }
  }
';
public $companyByName = /* GraphQL */ '
  query CompanyByName(
    $name: String
    $sortDirection: ModelSortDirection
    $filter: ModelCompanyFilterInput
    $limit: Int
    $nextToken: String
  ) {
    companyByName(
      name: $name
      sortDirection: $sortDirection
      filter: $filter
      limit: $limit
      nextToken: $nextToken
    ) {
      items {
        id
        legacyID
        name
        logo
        status
        legalCompanyName
        email
        phone
        qtySurveyAns
        website
        companyInsuranceStatus
        preferredOSDHPDClass
        types
        regions
        unspcs
        naics
        createdAt
        updatedAt
      }
      nextToken
    }
  }
';
public $companyByLegacyId = /* GraphQL */ '
  query CompanyByLegacyId(
    $legacyID: Int
    $sortDirection: ModelSortDirection
    $filter: ModelCompanyFilterInput
    $limit: Int
    $nextToken: String
  ) {
    companyByLegacyId(
      legacyID: $legacyID
      sortDirection: $sortDirection
      filter: $filter
      limit: $limit
      nextToken: $nextToken
    ) {
      items {
        id
        legacyID
        name
        logo
        status
        legalCompanyName
        email
        phone
        qtySurveyAns
        website
        companyInsuranceStatus
        preferredOSDHPDClass
        types
        regions
        unspcs
        naics
        createdAt
        updatedAt
      }
      nextToken
    }
  }
';
public $companyByLocation = /* GraphQL */ '
  query CompanyByLocation(
    $region: String
    $stateCityPostalCode: ModelLocationByRegionCompositeKeyConditionInput
    $sortDirection: ModelSortDirection
    $filter: ModelLocationFilterInput
    $limit: Int
    $nextToken: String
  ) {
    companyByLocation(
      region: $region
      stateCityPostalCode: $stateCityPostalCode
      sortDirection: $sortDirection
      filter: $filter
      limit: $limit
      nextToken: $nextToken
    ) {
      items {
        id
        region
        state
        street
        city
        postalCode
        companyID
        createdAt
        updatedAt
      }
      nextToken
    }
  }
';
public $applicationsByCompany = /* GraphQL */ '
  query ApplicationsByCompany(
    $companyID: ID
    $sortDirection: ModelSortDirection
    $filter: ModelCompaniesApplicationsFilterInput
    $limit: Int
    $nextToken: String
  ) {
    applicationsByCompany(
      companyID: $companyID
      sortDirection: $sortDirection
      filter: $filter
      limit: $limit
      nextToken: $nextToken
    ) {
      items {
        id
        companyID
        applicationID
        createdAt
        updatedAt
      }
      nextToken
    }
  }
';
public $companiesByApplication = /* GraphQL */ '
  query CompaniesByApplication(
    $applicationID: ID
    $sortDirection: ModelSortDirection
    $filter: ModelCompaniesApplicationsFilterInput
    $limit: Int
    $nextToken: String
  ) {
    companiesByApplication(
      applicationID: $applicationID
      sortDirection: $sortDirection
      filter: $filter
      limit: $limit
      nextToken: $nextToken
    ) {
      items {
        id
        companyID
        applicationID
        createdAt
        updatedAt
      }
      nextToken
    }
  }
';
public $applicationsByName = /* GraphQL */ '
  query ApplicationsByName(
    $name: String
    $sortDirection: ModelSortDirection
    $filter: ModelApplicationFilterInput
    $limit: Int
    $nextToken: String
  ) {
    applicationsByName(
      name: $name
      sortDirection: $sortDirection
      filter: $filter
      limit: $limit
      nextToken: $nextToken
    ) {
      items {
        id
        name
        createdAt
        updatedAt
      }
      nextToken
    }
  }
';

}
