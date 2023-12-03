<?php

namespace Mr4Lc\VietQr;

class VietQrConsts
{
    const PayloadFormatIndicatorId = '00';
    const PointOfInitiationMethodId = '01';
    const MerchantAccountInformationId = '38';
    const MerchantCategoryCodeId = '52';
    const TransactionCurrencyId = '53';
    const TransactionAmountId = '54';
    const TipOrConvenienceIndicatorId = '55';
    const ValueOfConvenienceFeeFixedId = '56';
    const ValueOfConvenienceFeePercentageId = '57';
    const CountryCodeId = '58';
    const MerchantNameId = '59';
    const MerchantCityId = '60';
    const PostalCodeId = '61';
    const AdditionalDataFieldTemplateId = '62';
    const CRCId = '63';
    const MerchantInformationLanguageTemplateId = '64';
    // const RFUForEMVCoId = '65' ~ '79';
    // const UnreservedTemplatesId = '80' ~ '99';

    const AdditionalDataFieldBillNumberId = '01';
    const AdditionalDataFieldMobileNumberId = '02';
    const AdditionalDataFieldStoreLabelId = '03';
    const AdditionalDataFieldLoyaltyNumberId = '04';
    const AdditionalDataFieldReferenceLabelId = '05';
    const AdditionalDataFieldCustomerLabelId = '06';
    const AdditionalDataFieldTerminalLabelId = '07';
    const AdditionalDataFieldPurposeOfTransactionId = '08';
    const AdditionalDataFieldAdditionalConsumerDataRequestId = '09';
    const ConsumerDataAddress = "A";
    const ConsumerDataMobile = "M";
    const ConsumerDataEmail = "E";

    const MerchantInformationLanguageLanguagePreferenceId = '00';
    const MerchantInformationLanguageMerchantNameAlternateLanguageId = '01';
    const MerchantInformationLanguageMerchantCityAlternateLanguageId = '02';
    // const MerchantInformationLanguageRFUForEMVCo = '03' ~ '99';

    // const AdditionalDataFieldRFUForEMVCoId = '10' ~ '49';
    // const AdditionalDataFieldPaymentSystemSpecificTemplatesId = '50' ~ '99';

    const PointOfInitiationMethodQRStatic = '11';
    const PointOfInitiationMethodQRDynamic = '12';

    const Visa1 = '02';
    const Visa2 = '03';
    const Mastercard1 = '04';
    const Mastercard2 = '05';
    const EMVCo1 = '06';
    const EMVCo2 = '07';
    const EMVCo3 = '08';
    const Discover1 = '09';
    const Discover2 = '10';
    const Amex1 = '11';
    const Amex2 = '12';
    const JCB1 = '13';
    const JCB2 = '14';
    const UnionPay1 = '15';
    const UnionPay2 = '16';
    // const EMVCo = '17' ~ '25';

    const AID = '00';
    const AID_GUID = 'A000000727';
    const BeneficiaryAccountId = '01';
    const ServiceCodeId = '02';
    const ServiceCodeToAccount = 'QRIBFTTA';
    const BankId = '00';
    const BankAccount = '01';

    const CurrencyCodeVND = '704';
    const CountryCodeVN = 'VN';
    const StandardCRC = 'CRC-CCITT (0xFFFF)';
    const LogoSize = 0.2;
    const PayloadFormatIndicator = '01';
    const FileName = 'UNDEFINED';
}
