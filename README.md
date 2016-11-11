**Agitation** is an e-commerce framework, based on Symfony2, focussed on
extendability through plugged-in APIs, UIs, payment modules and other
components.

## AgitCldrBundle

This bundle provides data for internationalization, such as countries,
currencies, timezones, locales.

This bundle ships its own copy of the ICU/CLDR data set and own data adapters,
rather than using the ones provided by Symfony’s `Intl` component.

Why is that? We need the relationships between objects (e.g. which currency is
used in which country), and this information is (currently) not provided by the
`Intl` bundle.
