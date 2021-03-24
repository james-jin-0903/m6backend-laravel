<?php


namespace App\GraphQL\coreDB;

trait CustomQuery {
  public $getUserWithCompanies =   '
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
            items {
                active
                id
                joinStatus 
                userWhoInvited
                company {
                    id
                    legacyID
                    email
                    logo
                    name
                    legalCompanyName
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
                }
            }
        }
        createdAt
        updatedAt
      }
    }
    ';
  
    public $getCompanyWithUsers = '
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
          companyInsuranceStatus
          preferredOSDHPDClass
          socialMediaLinks {
            icon
            name
            link
          }
          projectCapability {
            to
            from
          }
          projectSize {
            to
            from
          }
          links {
            uat
            prod
          }
          locations {
            id
            name
            mainLocation
            address
            postalCode
            city
            stateCode
            stateFull
            country
            lat
            lng
            locationType
          }
          users {
            items {
              id
              active
              joinStatus 
              userWhoInvited
              user {
                id
                drupalId
                cognitoId
                firstName
                lastName
                lastVisit
                profilePic
                location {
                  lat
                  lng
                  address
                }
                email
                phone
              }
            }
          }
          types
          regions 
          unspcs 
          naics 
          createdAt
          updatedAt
        }
      }
    ';
    
}
