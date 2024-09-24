export const AblumType = {
  ImageItem: class {
    /**
     * @param id 图片ID
     * @param url 图片URL
     * @param groupName 模板组名称
     */
    constructor({ id, url, groupName }) {
      this.id = id;
      this.url = url;
      this.groupName = groupName;
    }
  },
  Ablum: class {
    /**
     * @param upTaskId 上传任务ID
     * @param previewImageIndex 预览图片索引
     * @param {Array<AblumType.ImageItem>} imageList 图片列表
     */
    constructor({ upTaskId, previewImageIndex, imageList }) {
      this.upTaskId = upTaskId;
      this.previewImageIndex = previewImageIndex;
      this.imageList = imageList;
    }
  },
};

export const AblumStore = {
  setUpTaskId(store, upTaskId) {
    store.commit("updateBrowseringAlbumDetail", {
      upTaskId,
    });
  },
  getUpTaskId(store) {
    return store.state.browseringAlbumDetail.upTaskId;
  },

  /**
   * 设置当前查看的相册的图片列表
   * @param {import('vuex').Store} store Vuex store 对象
   * @param {Array<AblumType.ImageItem>} imageList 图片列表
   */
  setImageList(store, imageList) {
    // 校验每个元素
    const isValid = imageList.every(
      (item) => item instanceof AblumType.ImageItem
    );

    if (!isValid) {
      throw new Error(
        "The param [imageList] contains invalid items. All items must be instances of {AblumType.ImageItem}."
      );
    }

    store.commit("updateBrowseringAlbumDetail", {
      imageList,
    });
  },
  /**
   * 获取当前查看的相册的图片列表
   * @param {import('vuex').Store} store Vuex store 对象
   * @returns {Array<AblumType.ImageItem>} 图片列表
   */
  getImageList(store) {
    return store.state.browseringAlbumDetail.imageList;
  },
  setPreviewImageIndex(store, previewImageIndex) {
    store.commit("updateBrowseringAlbumDetail", {
      previewImageIndex,
    });
  },
  getPreviewImageIndex(store) {
    return store.state.browseringAlbumDetail.previewImageIndex;
  },
};
