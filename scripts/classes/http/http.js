const HttpClient = /** @class */ (function () {
  function HttpClient() {}

  HttpClient.extend = function (obj, src) {
    Object.keys(src).forEach(function (key) {
      obj[key] = src[key];
    });
    return obj;
  };
  /**
   *
   * @param method
   * @param url
   * @param options
   */
  HttpClient.request = function (method, url, options) {
    options = HttpClient.extend(this.defaultOptions, options);
    if (options.reportProgress === true) {
      js_divCarregando(options.reportMessage, "loading_message");
    }

    const accessToken = window.localStorage.getItem("access_token");
    const urlMatch = window.location.href.match(/[^\r\n]+(w\/[0-9]+)+/g);

    const locationUrl = urlMatch !== null ? urlMatch[0] : "";

    let params = {
      method: method,
      credentials: "include",
      headers: {
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest",
        Authorization: `Bearer ${accessToken}`,
      },
    };

    if (method !== "GET") {
      params.body = options.body;
    }

    if (!url.includes("http") && locationUrl !== "") {
      url = `${locationUrl}/${url}`;
    }

    return fetch(url, params).then(function (response) {
      if (options.reportProgress === true) {
        js_removeObj("loading_message");
      }
      return response.json();
    });
  };
  /**
   *
   * @param url
   * @param options
   */
  HttpClient.get = function (url, options) {
    if (options === void 0) {
      options = {};
    }
    return HttpClient.request("GET", url, options);
  };
  /**
   *
   * @param url
   * @param options
   */
  HttpClient.post = function (url, options) {
    if (options === void 0) {
      options = {};
    }
    return HttpClient.request("POST", url, options);
  };

  /**
   *
   * @param url
   * @param options
   */
  HttpClient.put = function (url, options) {
    if (options === void 0) {
      options = {};
    }
    return HttpClient.request("PUT", url, options);
  };

  /**
   *
   * @param url
   * @param options
   */
  HttpClient.delete = function (url, options) {
    if (options === void 0) {
      options = {};
    }
    return HttpClient.request("DELETE", url, options);
  };

  HttpClient.defaultOptions = {
    reportMessage: "Carregando...",
    reportProgress: true,
    body: new FormData(),
  };
  return HttpClient;
})();
