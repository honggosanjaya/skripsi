import React from 'react';
import PropTypes from 'prop-types';

export const ImagePreview = ({ dataUri }) => {
  return (
    <div className='demo-image-preview'>
      <img src={dataUri} />
    </div>
  );
};

ImagePreview.propTypes = {
  dataUri: PropTypes.string,
  isFullscreen: PropTypes.bool
};

export default ImagePreview;