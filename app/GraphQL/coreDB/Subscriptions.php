<?php
  /* eslint-disable */
  // this is an auto generated file. This will be overwritten
  namespace App\GraphQL\coreDB;
  class Subscriptions {

    public $onCreateUser = /* GraphQL */ '
      subscription OnCreateUser {
        onCreateUser {
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
    public $onUpdateUser = /* GraphQL */ '
      subscription OnUpdateUser {
        onUpdateUser {
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
    public $onDeleteUser = /* GraphQL */ '
      subscription OnDeleteUser {
        onDeleteUser {
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
    public $onCreateUserCompany = /* GraphQL */ '
      subscription OnCreateUserCompany {
        onCreateUserCompany {
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
            createdAt
            updatedAt
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onUpdateUserCompany = /* GraphQL */ '
      subscription OnUpdateUserCompany {
        onUpdateUserCompany {
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
            createdAt
            updatedAt
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onDeleteUserCompany = /* GraphQL */ '
      subscription OnDeleteUserCompany {
        onDeleteUserCompany {
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
            createdAt
            updatedAt
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onCreateInvite = /* GraphQL */ '
      subscription OnCreateInvite {
        onCreateInvite {
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
    public $onUpdateInvite = /* GraphQL */ '
      subscription OnUpdateInvite {
        onUpdateInvite {
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
    public $onDeleteInvite = /* GraphQL */ '
      subscription OnDeleteInvite {
        onDeleteInvite {
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
    public $onCreateCompany = /* GraphQL */ '
      subscription OnCreateCompany {
        onCreateCompany {
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
          naics {
            nextToken
          }
          applications {
            nextToken
          }
          locations {
            nextToken
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
          users {
            nextToken
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onUpdateCompany = /* GraphQL */ '
      subscription OnUpdateCompany {
        onUpdateCompany {
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
          naics {
            nextToken
          }
          applications {
            nextToken
          }
          locations {
            nextToken
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
          users {
            nextToken
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onDeleteCompany = /* GraphQL */ '
      subscription OnDeleteCompany {
        onDeleteCompany {
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
          naics {
            nextToken
          }
          applications {
            nextToken
          }
          locations {
            nextToken
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
          users {
            nextToken
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onCreateCompaniesNaics = /* GraphQL */ '
      subscription OnCreateCompaniesNaics {
        onCreateCompaniesNaics {
          id
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
            createdAt
            updatedAt
          }
          naic {
            id
            name
            code
            createdAt
            updatedAt
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onUpdateCompaniesNaics = /* GraphQL */ '
      subscription OnUpdateCompaniesNaics {
        onUpdateCompaniesNaics {
          id
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
            createdAt
            updatedAt
          }
          naic {
            id
            name
            code
            createdAt
            updatedAt
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onDeleteCompaniesNaics = /* GraphQL */ '
      subscription OnDeleteCompaniesNaics {
        onDeleteCompaniesNaics {
          id
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
            createdAt
            updatedAt
          }
          naic {
            id
            name
            code
            createdAt
            updatedAt
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onCreateNaic = /* GraphQL */ '
      subscription OnCreateNaic {
        onCreateNaic {
          id
          name
          code
          companies {
            nextToken
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onUpdateNaic = /* GraphQL */ '
      subscription OnUpdateNaic {
        onUpdateNaic {
          id
          name
          code
          companies {
            nextToken
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onDeleteNaic = /* GraphQL */ '
      subscription OnDeleteNaic {
        onDeleteNaic {
          id
          name
          code
          companies {
            nextToken
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onCreateProjectInfo = /* GraphQL */ '
      subscription OnCreateProjectInfo {
        onCreateProjectInfo {
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
            createdAt
            updatedAt
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onUpdateProjectInfo = /* GraphQL */ '
      subscription OnUpdateProjectInfo {
        onUpdateProjectInfo {
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
            createdAt
            updatedAt
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onDeleteProjectInfo = /* GraphQL */ '
      subscription OnDeleteProjectInfo {
        onDeleteProjectInfo {
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
            createdAt
            updatedAt
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onCreateLocation = /* GraphQL */ '
      subscription OnCreateLocation {
        onCreateLocation {
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
            createdAt
            updatedAt
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onUpdateLocation = /* GraphQL */ '
      subscription OnUpdateLocation {
        onUpdateLocation {
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
            createdAt
            updatedAt
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onDeleteLocation = /* GraphQL */ '
      subscription OnDeleteLocation {
        onDeleteLocation {
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
            createdAt
            updatedAt
          }
          createdAt
          updatedAt
        }
      }
    ';
    public $onCreateGeneralInfo = /* GraphQL */ '
      subscription OnCreateGeneralInfo {
        onCreateGeneralInfo {
          id
          description
          social
          createdAt
          updatedAt
        }
      }
    ';
    public $onUpdateGeneralInfo = /* GraphQL */ '
      subscription OnUpdateGeneralInfo {
        onUpdateGeneralInfo {
          id
          description
          social
          createdAt
          updatedAt
        }
      }
    ';
    public $onDeleteGeneralInfo = /* GraphQL */ '
      subscription OnDeleteGeneralInfo {
        onDeleteGeneralInfo {
          id
          description
          social
          createdAt
          updatedAt
        }
      }
    ';
    public $onCreateCompaniesApplications = /* GraphQL */ '
      subscription OnCreateCompaniesApplications {
        onCreateCompaniesApplications {
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
    public $onUpdateCompaniesApplications = /* GraphQL */ '
      subscription OnUpdateCompaniesApplications {
        onUpdateCompaniesApplications {
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
    public $onDeleteCompaniesApplications = /* GraphQL */ '
      subscription OnDeleteCompaniesApplications {
        onDeleteCompaniesApplications {
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
    public $onCreateApplication = /* GraphQL */ '
      subscription OnCreateApplication {
        onCreateApplication {
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
    public $onUpdateApplication = /* GraphQL */ '
      subscription OnUpdateApplication {
        onUpdateApplication {
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
    public $onDeleteApplication = /* GraphQL */ '
      subscription OnDeleteApplication {
        onDeleteApplication {
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
}
