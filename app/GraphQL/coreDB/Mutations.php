<?php

namespace App\GraphQL\coreDB;

trait Mutations
{
/* eslint-disable */
// this is an auto generated file. This will be overwritten

public $createUser = /* GraphQL */ '
  mutation CreateUser(
    $input: CreateUserInput!
    $condition: ModelUserConditionInput
  ) {
    createUser(input: $input, condition: $condition) {
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
public $updateUser = /* GraphQL */ '
  mutation UpdateUser(
    $input: UpdateUserInput!
    $condition: ModelUserConditionInput
  ) {
    updateUser(input: $input, condition: $condition) {
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
public $deleteUser = /* GraphQL */ '
  mutation DeleteUser(
    $input: DeleteUserInput!
    $condition: ModelUserConditionInput
  ) {
    deleteUser(input: $input, condition: $condition) {
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
public $createUserCompany = /* GraphQL */ '
  mutation CreateUserCompany(
    $input: CreateUserCompanyInput!
    $condition: ModeluserCompanyConditionInput
  ) {
    createUserCompany(input: $input, condition: $condition) {
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
public $updateUserCompany = /* GraphQL */ '
  mutation UpdateUserCompany(
    $input: UpdateUserCompanyInput!
    $condition: ModeluserCompanyConditionInput
  ) {
    updateUserCompany(input: $input, condition: $condition) {
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
public $deleteUserCompany = /* GraphQL */ '
  mutation DeleteUserCompany(
    $input: DeleteUserCompanyInput!
    $condition: ModeluserCompanyConditionInput
  ) {
    deleteUserCompany(input: $input, condition: $condition) {
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
public $createInvite = /* GraphQL */ '
  mutation CreateInvite(
    $input: CreateInviteInput!
    $condition: ModelInviteConditionInput
  ) {
    createInvite(input: $input, condition: $condition) {
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
public $updateInvite = /* GraphQL */ '
  mutation UpdateInvite(
    $input: UpdateInviteInput!
    $condition: ModelInviteConditionInput
  ) {
    updateInvite(input: $input, condition: $condition) {
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
public $deleteInvite = /* GraphQL */ '
  mutation DeleteInvite(
    $input: DeleteInviteInput!
    $condition: ModelInviteConditionInput
  ) {
    deleteInvite(input: $input, condition: $condition) {
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
public $createCompany = /* GraphQL */ '
  mutation CreateCompany(
    $input: CreateCompanyInput!
    $condition: ModelCompanyConditionInput
  ) {
    createCompany(input: $input, condition: $condition) {
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
public $updateCompany = /* GraphQL */ '
  mutation UpdateCompany(
    $input: UpdateCompanyInput!
    $condition: ModelCompanyConditionInput
  ) {
    updateCompany(input: $input, condition: $condition) {
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
public $deleteCompany = /* GraphQL */ '
  mutation DeleteCompany(
    $input: DeleteCompanyInput!
    $condition: ModelCompanyConditionInput
  ) {
    deleteCompany(input: $input, condition: $condition) {
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
public $createProjectInfo = /* GraphQL */ '
  mutation CreateProjectInfo(
    $input: CreateProjectInfoInput!
    $condition: ModelProjectInfoConditionInput
  ) {
    createProjectInfo(input: $input, condition: $condition) {
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
public $updateProjectInfo = /* GraphQL */ '
  mutation UpdateProjectInfo(
    $input: UpdateProjectInfoInput!
    $condition: ModelProjectInfoConditionInput
  ) {
    updateProjectInfo(input: $input, condition: $condition) {
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
public $deleteProjectInfo = /* GraphQL */ '
  mutation DeleteProjectInfo(
    $input: DeleteProjectInfoInput!
    $condition: ModelProjectInfoConditionInput
  ) {
    deleteProjectInfo(input: $input, condition: $condition) {
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
public $createLocation = /* GraphQL */ '
  mutation CreateLocation(
    $input: CreateLocationInput!
    $condition: ModelLocationConditionInput
  ) {
    createLocation(input: $input, condition: $condition) {
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
public $updateLocation = /* GraphQL */ '
  mutation UpdateLocation(
    $input: UpdateLocationInput!
    $condition: ModelLocationConditionInput
  ) {
    updateLocation(input: $input, condition: $condition) {
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
public $deleteLocation = /* GraphQL */ '
  mutation DeleteLocation(
    $input: DeleteLocationInput!
    $condition: ModelLocationConditionInput
  ) {
    deleteLocation(input: $input, condition: $condition) {
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
public $createGeneralInfo = /* GraphQL */ '
  mutation CreateGeneralInfo(
    $input: CreateGeneralInfoInput!
    $condition: ModelGeneralInfoConditionInput
  ) {
    createGeneralInfo(input: $input, condition: $condition) {
      id
      description
      social
      createdAt
      updatedAt
    }
  }
';
public $updateGeneralInfo = /* GraphQL */ '
  mutation UpdateGeneralInfo(
    $input: UpdateGeneralInfoInput!
    $condition: ModelGeneralInfoConditionInput
  ) {
    updateGeneralInfo(input: $input, condition: $condition) {
      id
      description
      social
      createdAt
      updatedAt
    }
  }
';
public $deleteGeneralInfo = /* GraphQL */ '
  mutation DeleteGeneralInfo(
    $input: DeleteGeneralInfoInput!
    $condition: ModelGeneralInfoConditionInput
  ) {
    deleteGeneralInfo(input: $input, condition: $condition) {
      id
      description
      social
      createdAt
      updatedAt
    }
  }
';
public $createCompaniesApplications = /* GraphQL */ '
  mutation CreateCompaniesApplications(
    $input: CreateCompaniesApplicationsInput!
    $condition: ModelCompaniesApplicationsConditionInput
  ) {
    createCompaniesApplications(input: $input, condition: $condition) {
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
public $updateCompaniesApplications = /* GraphQL */ '
  mutation UpdateCompaniesApplications(
    $input: UpdateCompaniesApplicationsInput!
    $condition: ModelCompaniesApplicationsConditionInput
  ) {
    updateCompaniesApplications(input: $input, condition: $condition) {
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
public $deleteCompaniesApplications = /* GraphQL */ '
  mutation DeleteCompaniesApplications(
    $input: DeleteCompaniesApplicationsInput!
    $condition: ModelCompaniesApplicationsConditionInput
  ) {
    deleteCompaniesApplications(input: $input, condition: $condition) {
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
public $createApplication = /* GraphQL */ '
  mutation CreateApplication(
    $input: CreateApplicationInput!
    $condition: ModelApplicationConditionInput
  ) {
    createApplication(input: $input, condition: $condition) {
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
public $updateApplication = /* GraphQL */ '
  mutation UpdateApplication(
    $input: UpdateApplicationInput!
    $condition: ModelApplicationConditionInput
  ) {
    updateApplication(input: $input, condition: $condition) {
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
public $deleteApplication = /* GraphQL */ '
  mutation DeleteApplication(
    $input: DeleteApplicationInput!
    $condition: ModelApplicationConditionInput
  ) {
    deleteApplication(input: $input, condition: $condition) {
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
public $createRapidTicket = /* GraphQL */ '
  mutation CreateRapidTicket(
    $input: CreateRapidTicketInput!
    $condition: ModelRapidTicketConditionInput
  ) {
    createRapidTicket(input: $input, condition: $condition) {
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
public $updateRapidTicket = /* GraphQL */ '
  mutation UpdateRapidTicket(
    $input: UpdateRapidTicketInput!
    $condition: ModelRapidTicketConditionInput
  ) {
    updateRapidTicket(input: $input, condition: $condition) {
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
public $deleteRapidTicket = /* GraphQL */ '
  mutation DeleteRapidTicket(
    $input: DeleteRapidTicketInput!
    $condition: ModelRapidTicketConditionInput
  ) {
    deleteRapidTicket(input: $input, condition: $condition) {
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

}
