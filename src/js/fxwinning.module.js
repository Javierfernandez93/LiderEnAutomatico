import { Http } from './http.module.js';

const PATH = getMainPath()

class Fxwinning extends Http {
    constructor() {
        super();
    }
    uploadImageSign(data, progress, callback) {
        return this.callFile(PATH+'/app/application/uploadImageSign.php', data, callback, progress);
    }
    uploadImageSignAsString(data, callback) {
        return this.call(PATH+'/app/application/uploadImageSignAsString.php', data, callback, null, null, 'POST');
    }
    makeFxWinninDocument(data,callback) {
        return this.call(PATH+'/app/application/autoMakeFxWinninDocument.php', data, callback);
    }
}

export { Fxwinning }