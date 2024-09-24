<?php

namespace app\common\enum;

class BusinessErrorCodeEnum
{
    const COMMON_ERROR = 0;
    const COMMON_SUCCESS = 1;
    const USER_TOKEN_EXPIRED = -1;
    const USER_UPLOAD_IMAGE_INVALID = 10001;
    const USER_SENS_SMD_EXCEED_RATE_LIMIT = 10002;
}
