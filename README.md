This documentation explain how to use our subscribeHelper.

# What can I use it for
Take advantage of our subscribeHelper to fullfill Smiile subscribe form from your users informations. Users will also be linked to your Smiile account and directly validated.

Our subscribe form need the following inputs to be fullfilled before subscribing can be completed. Smiile subscribeHelper will help prefilling some of them so your user won't have to do it by himeself:

what | field | subscribeHelper
:---: | :---: | :---:
Civility | civility | X
First Name | name | X
Last Name | lastName | X
Email address | mail | X
Postal address | address | X
Password | password |
Cgu checkbox | cgu |


# How it works
### Get a api credentials from Smiile
Please contact SmiileTeam to get a provider key and a secret code

### Generate the subscribe link
The link to generate is a concatenation of your user informations fields and a md5 checksum from those informations in a specific order and your private key.

```
link = https://app.smiile.com/inscription?
    + providerKey={YOUR_PROVIDER_KEY}
    + &address={user_postal_adress}
    + &civility={user_civility} (1=male|2=female)
    + &name={user_first_name}
    + &lastName={user_last_name}
    + &mail={user_email}
    + &checksum=
        md5(
            trim({address})
            + trim({user_civility})
            + trim({user_first_name})
            + trim({user_last_name})
            + trim({user_email})
            + {YOUR_SECRET_CODE}
        )
```


# Library
We developped a PHP class.
You can find it [here](https://github.com/smiileTeam/subscribeHelper)
